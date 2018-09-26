<?php

class User
{
    private static $hash_algo = "sha256";

    /**
     * Calculate the count of user records.
     * @return integer
     * @throws PdoDbException
     */
    public static function count()
    {
        global $pdoDb;

        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "user");
        return $rows[0]['count'];
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
            $_POST['password'] = self::hashPassword($_POST['password']); // OK. Save hashed password

            $excludedFields = array('id', 'domain_id');
            if ($exclude_pwd) $excludedFields[] = 'password';

            $pdoDb->setExcludedFields($excludedFields);

            $pdoDb->addSimpleWhere('id', $_GET['id'], 'AND');
            $pdoDb->addSimpleWhere('domain_id', domain_id::get());

            $result = $pdoDb->request('UPDATE', 'user');
        } catch (Exception $e) {
            error_log("Unable to update the user record. Error reported: " . $e->getMessage());
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
        try {
            $_POST['password'] = self::hashPassword($_POST['password']); // OK. Save hashed password
            $pdoDb->setExcludedFields('id');
            $id = $pdoDb->request('INSERT', 'user');
        } catch (Exception $e) {
            echo '<h1>Unable to add the new ' . TB_PREFIX . 'user record.</h1>';
            $id = 0;
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
     * @throws PdoDbException
     */
    public static function verifyPassword($username, $password)
    {
        global $pdoDb_admin;

        try {
            $pdoDb_admin->addSimpleWhere("username", $username);
            $rows = $pdoDb_admin->request('SELECT', 'user');
            if (empty($rows)) {
                error_log("User::verifyPassword() - No such username[$username]");
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
            // Old password match. Update user with new password.
            $pdoDb_admin->addSimpleWhere('username', $username);
            $pdoDb_admin->setFauxPost(array('password' => $hash));

            $result = $pdoDb_admin->request('UPDATE', 'user');
            if ($result) {
                return true;
            }
            error_log("User::verifyPassword() - Unable to update password hash for username[$username]");
        }
        return false;

    }

    /**
     * Get all user role records.
     * @return array of <b>user_role</b> records.
     * @throws PdoDbException
     */
    public static function getUserRoles()
    {
        global $pdoDb;
        $pdoDb->setOrderBy(new OrderBy("id"));
        $pdoDb->setSelectList(array("id", "name"));
        $rows = $pdoDb->request("SELECT", "user_role");
        return $rows;
    }

    /**
     * Get a specific <b>user</b> table record.
     * @param int $id of the record to retrieve.
     * @return user record.
     * @throws PdoDbException
     */
    public static function getUser($id)
    {
        global $LANG, $pdoDb;

        // Note that id is a unique identifier irrespective of the domain_id.
        $pdoDb->addSimpleWhere("u.id", $id);

        $list = array("ur.name AS role_name");
        $pdoDb->setSelectList($list);
        $pdoDb->setSelectAll(true);

        $caseStmt = new CaseStmt("enabled", "enabled_txt");
        $caseStmt->addWhen("=", ENABLED, $LANG['enabled']);
        $caseStmt->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($caseStmt);

        $join = new Join("LEFT", "user_role", "ur");
        $join->addSimpleItem("ur.id", new DbField("role_id"));
        $pdoDb->addToJoins($join);

        $rows = $pdoDb->request("SELECT", "user", "u");
        return (empty($rows) ? array() : $rows[0]);
    }

}
