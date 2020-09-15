<?php
namespace Inc\Claz;

/**
 * Class User
 * @package Inc\Claz
 */
class User
{
    private static string $hashAlgorithm = PASSWORD_DEFAULT;
    public static string $usernamePattern = "(?=^.{4,}$)([A-Za-z0-9][A-Za-z0-9@_\-\.#\$]+)$";

    /**
     * Calculate the count of user records.
     * @return int
     */
    public static function count(): int
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->setSelectList('id');
            $rows = $pdoDb->request("SELECT", "user");
        } catch (PdoDbException $pde) {
            error_log("User::count() - Error: " . $pde->getMessage());
        }
        return count($rows);
    }

    /**
     * Retrieve a specific <b>user</b> table record.
     * @param int $id of the record to retrieve.
     * @return array of User fields else empty array.
     */
    public static function getOne(int $id): array
    {
        return self::getUsers($id);
    }

    /**
     * Retrieve all users based on $enabled setting
     * @param bool $enabled if true only enabled user records will be returned.
     * @return array
     */
    public static function getAll(bool $enabled = false): array
    {
        return self::getUsers(null, $enabled);
    }

    /**
     * Retrieve all user records
     * @param int|null $id If no null, the id of the specific user record to retrieve.
     * @param bool Set to true if only enabled user records should be retrieved.
     * @return array Rows retrieved
     */
    private static function getUsers(?int $id = null, bool $enabled = false): array {
        global $LANG, $pdoDb;

        $results = [];
        try {
            session_name('SiAuth');
            session_start();

            // If user role is customer or biller, then restrict invoices to those they have access to.
            if ($_SESSION['role_name'] == 'biller' || $_SESSION['role_name'] == 'customer') {
                $pdoDb->addSimpleWhere('u.id', $_SESSION['id'], 'AND');
            }

            if (isset($id)) {
                $pdoDb->addSimpleWhere('u.id', $id, 'AND');
            }
            if ($enabled) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setOrderBy('username');

            $list = [
                'id',
                'username',
                'email',
                'user_id',
                'enabled',
                'role_id',
                'ur.name AS role_name'
            ];
            $pdoDb->setSelectList($list);

            $caseStmt = new CaseStmt("u.enabled", "enabled_text");
            $caseStmt->addWhen("=", ENABLED, $LANG['enabled']);
            $caseStmt->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($caseStmt);

            $join = new Join("LEFT", "user_role", "ur");
            $join->addSimpleItem("ur.id", new DbField("role_id"));
            $pdoDb->addToJoins($join);

            $rows = $pdoDb->request("SELECT", "user", "u");

            foreach ($rows as $row) {
                if ($row['role_name'] == 'customer') {
                    $cid = $row['user_id'];
                    $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
                    $pdoDb->addSimpleWhere("id", $cid);
                    $cust = $pdoDb->request("SELECT", "customers");
                    $uid = $cid . " - " . (empty($cust[0]['name']) ? "Unknown Customer" : $cust[0]['name']);
                } elseif ($row['role_name'] == 'biller') {
                    $bid = $row['user_id'];
                    $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
                    $pdoDb->addSimpleWhere("id", $bid);
                    $biller = $pdoDb->request("SELECT", "biller");
                    $uid = $bid . " - " . (empty($biller[0]['name']) ? "Unknown Biller" : $biller[0]['name']);
                } else {
                    $uid = "0 - Standard User";
                }
                $row['uid'] = $uid;
                $results[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("User::getAll() - error: " . $pde->getMessage());
        }
        if (empty($results)) {
            return [];
        }
        return isset($id) ? $results[0] : $results;
    }

    /**
     * Update user record.
     * @param bool $exclude_pwd
     * @return bool true if update succeeded, otherwise false.
     */
    public static function updateUser(bool $exclude_pwd): bool
    {
        global $pdoDb;
        try {
            $excludedFields = ['id', 'domain_id'];
            if ($exclude_pwd) {
                $excludedFields[] = 'password';
            } else {
                $_POST['password'] = self::hashPassword($_POST['password']); // OK. Save hashed password
            }

            $pdoDb->setExcludedFields($excludedFields);

            $pdoDb->addSimpleWhere('id', $_POST['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $result = $pdoDb->request('UPDATE', 'user');
        } catch (PdoDbException $pde) {
            error_log("User::updateUser() - Error:" . $pde->getMessage());
            $result = false;
        }
        return $result;
    }

    /**
     * Insert a new user record using field in the $_POST global.
     * @return int ID assigned to new record or 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertUser(): int
    {
        global $pdoDb;

        // Default role to administrator. Can be changed in edit screen
        // which must be performed to enable this user.
        $pdoDb->addSimpleWhere('name', 'administrator');
        $rows = $pdoDb->request('SELECT', 'user_role');
        $_POST['role_id'] = $rows[0]['id'];
        $_POST['enabled'] = DISABLED;
        $_POST['user_id'] = 0;

        $id = 0;
        try {
            $_POST['password'] = self::hashPassword($_POST['password']); // OK. Save hashed password
            $_POST['domain_id'] = DomainId::get();
            $pdoDb->setExcludedFields('id');
            $id = $pdoDb->request('INSERT', 'user');
        } catch (PdoDbException $pde) {
            error_log("User::insertUser() - Error: " . $pde->getMessage());
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'user record.</h1>';
        }
        return $id;
    }

    private static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify password for specified username.
     * @param string $username
     * @param string $password
     * @return bool true if password is valid; false if not.
     */
    public static function verifyPassword(string $username, string $password): bool
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere("username", $username);
            $rows = $pdoDbAdmin->request('SELECT', 'user');
            if (empty($rows)) {
                return false;
            }
            $user = $rows[0];
        } catch (PdoDbException $pde) {
            error_log("User::verifyPassword() - No such username[$username] - " . $pde->getMessage());
            return false;
        }

        $userPassword = $user['password'];
        if (password_verify($password, $userPassword)) {
            return true;
        }

        $hash = hash('sha256', $password);
        $bOk = $hash === $userPassword;

        if (!$bOk) {
            $md5Password = MD5($password);
            $bOk = $userPassword === $md5Password;
        }

        if ($bOk) {
            try {
                $hash = password_hash($password, self::$hashAlgorithm);
                // Old password match. Update user with new password.
                $pdoDbAdmin->addSimpleWhere('username', $username);
                $pdoDbAdmin->setFauxPost(['password' => $hash]);

                if ($pdoDbAdmin->request('UPDATE', 'user')) {
                    return true;
                }
            } catch (PdoDbException $pde) {
                error_log("User::verifyPassword() - Error: " . $pde->getMessage());
            }
            error_log("User::verifyPassword() - Unable to update password hash for username[$username]");
        }
        return false;

    }

    /**
     * Get all user role records.
     * @return array of <b>user_role</b> records.
     */
    public static function getUserRoles()
    {
        global $pdoDb;
        try {
            $pdoDb->setOrderBy(new OrderBy("id"));
            $pdoDb->setSelectList(["id", "name"]);
            $rows = $pdoDb->request("SELECT", "user_role");
        } catch (PdoDbException $pde) {
            error_log("User::getRoles() - PdoDbException: " . $pde->getMessage());
            return [];
        }
        return $rows;
    }

}
