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

        $count = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "user");
            if (!empty($rows)) {
                $count = $rows[0]['count'];
            }
        } catch (PdoDbException $pde) {
            error_log("User::count() - Error: " . $pde->getMessage());
        }
        return $count;
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
            $pdoDb->addSimpleWhere('domain_id', $_POST['domain_id']);

            $result = $pdoDb->request('UPDATE', 'user');
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
            error_log("User::getUserRoles() - PdoDbException: " . $pde->getMessage());
            return array();
        }
        return $rows;
    }

    /**
     * Get a specific <b>user</b> table record.
     * @param int $id of the record to retrieve.
     * @return array of User fields else empty array.
     */
    public static function getUser($id)
    {
        global $LANG, $pdoDb;

        try {
            // Note that id is a unique identifier irrespective of the domain_id.
            $pdoDb->addSimpleWhere("u.id", $id);

            $list = array("u.id as id", "ur.name AS role_name");
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
        } catch (PdoDbException $pde) {
            error_log("User::getUser($id) - PdoException: " . $pde->getMessage());
            return array();
        }
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * Select records to display in the flexigrid list
     * @param string $type if 'count', a count of qualified records is returned, otherwise
     *          array of qualified rows from the table is returned.
     * @param string $dir
     * @param string $sort
     * @param int $rp
     * @param int $page
     * @return array|int
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page) {
        global $LANG, $pdoDb;

        $count = ($type == 'count');
        $result = array();
        try {
            $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : "";
            $qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : "";
            if (!empty($qtype) && !empty($query)) {
                if (in_array($qtype, array('username', 'email', 'ur.name'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", $query, false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            if ($count) {
                $pdoDb->addToFunctions("count(*) AS count");
                $rows = $pdoDb->request("SELECT", "user");
                return $rows[0]['count'];
            }

            $dir = (!preg_match('/^desc$/iD', $dir) ? "D" : "A");
            if (!in_array($sort, array('username', 'email', 'role'))) $sort = "username";
            $pdoDb->setOrderBy(array($sort, $dir));

            if (intval($page) != $page) {
                $page = 1;
            }

            if (intval($rp) != $rp) {
                $rp = 25;
            }
            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            $list = array("id", "username", "email", "user_id", "enabled", "ur.name AS role_name");
            $pdoDb->setSelectList($list);

            $caseStmt = new CaseStmt("u.enabled", "enabled");
            $caseStmt->addWhen("=", ENABLED, $LANG['enabled']);
            $caseStmt->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($caseStmt);

            $join = new Join("LEFT", "user_role", "ur");
            $join->addSimpleItem("ur.id", new DbField("role_id"));
            $pdoDb->addToJoins($join);

            $rows = $pdoDb->request("SELECT", "user", "u");

            $result = array();
            foreach ($rows as $row) {
                if ($row['role_name'] == 'customer') {
                    $cid = $row['user_id'];
                    $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
                    $pdoDb->addSimpleWhere("id", $cid);
                    $cust = $pdoDb->request("SELECT", "customers");
                    $uid = $cid . " - " . (empty($cust[0]['name']) ? "Unknown Customer" : $cust[0]['name']);
                } else if ($row['role_name'] == 'biller') {
                    $bid = $row['user_id'];
                    $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
                    $pdoDb->addSimpleWhere("id", $bid);
                    $bilr = $pdoDb->request("SELECT", "biller");
                    $uid = $bid . " - " . (empty($bilr[0]['name']) ? "Unknown Biller" : $bilr[0]['name']);
                } else {
                    $uid = "0 - Standard User";
                }
                $row['uid'] = $uid;
                $result[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("User::xmlSql() - error: " . $pde->getMessage());
        }
        return ($count ? 0 : $result);
    }

}
