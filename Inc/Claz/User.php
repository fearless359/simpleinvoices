<?php
namespace Inc\Claz;

/**
 * Class User
 * @package Inc\Claz
 */
class User
{
    private static $hash_algo = "sha256";
    public static $username_pattern = "(?=^.{4,}$)([A-Za-z0-9][A-Za-z0-9@_\-\.#\$]+)$";

    /**
     * Calculate the count of user records.
     * @return integer
     */
    public static function count()
    {
        global $pdoDb;

        $rows = array();
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
    public static function getOne($id)
    {
        return self::getUsers($id);
    }

    /**
     * Retrieve all users based on $enabled setting
     * @param boolean $enabled if true only enabled user records will be returned.
     * @return array
     */
    public static function getAll($enabled = false)
    {
        return self::getUsers(null, $enabled);
    }

    /**
     * Retrieve all user records
     * @param int $id If no null, the id of the specifiec user record to retrieve.
     * @param boolean Set to true if only enabled user records should be retrieved.
     * @return array Rows retrieved
     */
    private static function getUsers($id = null, $enabled = false) {
        global $LANG, $pdoDb;

        $results = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('u.id', $id, 'AND');
            }
            IF ($enabled) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, 'AND');
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setOrderBy('username');

            $list = array(
                'id',
                'username',
                'email',
                'user_id',
                'enabled',
                'role_id',
                'ur.name AS role_name'
            );
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
                } else if ($row['role_name'] == 'biller') {
                    $bid = $row['user_id'];
                    $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
                    $pdoDb->addSimpleWhere("id", $bid);
                    $bilr = $pdoDb->request("SELECT", "biller");
                    $uid = $bid . " - " . (empty($bilr[0]['name']) ? "Unknown Biller" : $bilr[0]['name']);
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
            return array();
        }
        return (isset($id) ? $results[0] : $results);
    }

    /**
     * Update user record.
     * @param $exclude_pwd
     * @return bool true if update succeeded, otherwise false.
     */
    public static function updateUser($exclude_pwd)
    {
        global $pdoDb;
        try {
            $excludedFields = array('id', 'domain_id');
            if ($exclude_pwd) {
                $excludedFields[] = 'password';
            } else {
                $_POST['password'] = self::hashPassword($_POST['password']); // OK. Save hashed password
            }

            $pdoDb->setExcludedFields($excludedFields);

            $pdoDb->addSimpleWhere('id', $_POST['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
$pdoDb->debugOn();
            $result = $pdoDb->request('UPDATE', 'user');
$pdoDb->debugOff();
        } catch (PdoDbException $pde) {
            error_log("User::updateUser() - Error:" . $pde->getMessage());
            $result = false;
        }
        return $result;
    }

    /**
     * Insert a new user record using field in the $_POST global.
     * @return integer ID assigned to new record or 0 if insert failed.
     */
    public static function insertUser()
    {
        global $pdoDb;

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

    /**
     * @param $password
     * @return string
     */
    private static function hashPassword($password)
    {
        return hash("sha256", $password);
    }

    /**
     * @param string $username
     * @param $password
     * @return bool
     */
    public static function verifyPassword($username, $password)
    {
        global $pdoDb_admin;

        try {
            $pdoDb_admin->addSimpleWhere("username", $username);
            $rows = $pdoDb_admin->request('SELECT', 'user');
            if (empty($rows)) {
                return false;
            }
            $user = $rows[0];
        } catch (PdoDbException $pde) {
            error_log("User::verifyPassword() - No such username[$username] - " . $pde->getMessage());
            return false;
        }

        $user_password = $user['password'];
        $hash = hash(self::$hash_algo, $password);
        if ($user_password == $hash) {
            return true;
        }

        // Use for old password hashes.
        $md5_pwd = MD5($password);
        if ($user_password == $md5_pwd) {
            try {
                // Old password match. Update user with new password.
                $pdoDb_admin->addSimpleWhere('username', $username);
                $pdoDb_admin->setFauxPost(array('password' => $hash));

                $result = $pdoDb_admin->request('UPDATE', 'user');
                if ($result) {
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
            $pdoDb->setSelectList(array("id", "name"));
            $rows = $pdoDb->request("SELECT", "user_role");
        } catch (PdoDbException $pde) {
            error_log("User::getRoles() - PdoDbException: " . $pde->getMessage());
            return array();
        }
        return $rows;
    }

}
