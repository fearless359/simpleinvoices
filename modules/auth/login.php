<?php

use Inc\Claz\DbField;
use Inc\Claz\Join;
use Inc\Claz\Log;
use Inc\Claz\PdoDbException;
use Inc\Claz\User;
use Inc\Claz\Util;

/*
 *  Script: login.php
 *      Login page
 *
 *  Last modified:
 *		2018-09-24 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 */

global $module, $pdoDb, $smarty, $view;

Util::allowDirectAccess();

$menu = false;

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) {
    define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");
}

$errorMessage = '';
Util::loginLogo($smarty);
Log::out("login.php - _POST - " . print_r($_POST, true));
Log::out("login.php - _SESSION - " . print_r($_SESSION, true));
if (empty($_POST['user']) || empty($_POST['pass'])) {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
} else {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    if (User::verifyPassword($username, $password)) {
        try {
            $jn = new Join('LEFT', 'user_role', 'r');
            $jn->addSimpleItem('u.role_id', new DbField('r.id'));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'user_domain', 'ud');
            $jn->addSimpleItem('u.domain_id', new DbField('ud.id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList([
                'u.id',
                'u.username',
                'u.email',
                new DbField('r.name', 'role_name'),
                'u.domain_id',
                'u.user_id',
                new DbField('ud.name', 'domain_name')
            ]);

            $pdoDb->addSimpleWhere('u.username', $username, 'AND');
            $pdoDb->addSimpleWhere('u.enabled', ENABLED);

            $rows = $pdoDb->request('SELECT', 'user', 'u');
        } catch (PdoDbException $pde) {
            error_log("modules.auth.login.php: Error(3): " . $pde->getMessage());
            exit("modules.auth.login.php(3) - Database access error");
        }

        if (empty($rows)) {
            $_SESSION['id'] = null;
            $_SESSION['role_name'] = null;
            $_SESSION['user_id'] = null;
        } else {
            $row = $rows[0];
            $_SESSION['id'] = $row['id'];
            $_SESSION['role_name'] = $row['role_name'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['domain_id'] = $row['domain_id'];
            $_SESSION['email'] = $row['email'];
        }

        if (isset($_SESSION['role_name'])) {
            Log::out("login.php - role_name[{$_SESSION['role_name']}] user_id[{$_SESSION['user_id']}]");
            if ($_SESSION['role_name'] == 'biller' && $_SESSION['user_id'] > 0) {
                header("Location: index.php?module=billers&view=view&id={$_SESSION['user_id']}");
                exit();
            } elseif ($_SESSION['role_name'] == 'customer' && $_SESSION['user_id'] > 0) {
                header("Location: index.php?module=customers&view=view&id={$_SESSION['user_id']}");
                exit();
            }
            header('Location: index.php?module=invoices&view=manage');
            exit();
        }
    }
    $errorMessage = STD_LOGIN_FAILED_MSG;
}
// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage", $errorMessage);
