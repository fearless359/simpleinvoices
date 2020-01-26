<?php

use Inc\Claz\DbField;
use Inc\Claz\Join;
use Inc\Claz\Log;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
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

global $patchCount, $pdoDb, $smarty;

Util::allowDirectAccess();

$menu = false;

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) {
    define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");
}

try {
    \Zend_Session::start();
} catch (\Zend_Session_Exception $zse) {
    error_log("modules.auth.login.php: Error: " . $zse->getMessage());
    die("modules.auth.login.php - Unable to start a session.");
}
$errorMessage = '';
Util::loginLogo($smarty);
Log::out("login.php _POST - " . print_r($_POST,true), Zend_Log::DEBUG);
if (empty($_POST['user']) || empty($_POST['pass'])) {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
} else {
    $username = $_POST['user'];
    $password  = $_POST['pass'];
    if (User::verifyPassword($username, $password)) {
        try {
            Zend_Session::start();

            $timeout = SystemDefaults::getDefaultSessionTimeout();
            if ($timeout <= 0) {
                $timeout = 60;
            }

            $authNamespace = new \Zend_Session_Namespace('Zend_Auth');
            $authNamespace->setExpirationSeconds($timeout * 60);
        } catch (Zend_Session_Exception $zse) {
            error_log("modules.auth.login.php: Error(2): " . $zse->getMessage());
            die("modules.auth.login.php(2) - Unable to start a session.");
        }

        try {
            $jn = new Join('LEFT', 'user_role', 'r');
            $jn->addSimpleItem('u.role_id', new DbField('r.id'));
            $pdoDb->addToJoins($jn);
            
            $jn = new Join('LEFT', 'user_domain', 'ud');
            $jn->addSimpleItem('u.domain_id', new DbField('ud.id'));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList(array('u.id', 'u.username', 'u.email', new DbField('r.name', 'role_name'),
                'u.domain_id', 'u.user_id', new DbField('ud.name', 'domain_name')));

            $pdoDb->addSimpleWhere('u.username', $username, 'AND');
            $pdoDb->addSimpleWhere('u.enabled', ENABLED);

            $rows = $pdoDb->request('SELECT', 'user', 'u');
        } catch (PdoDbException $pde) {
            error_log("modules.auth.login.php: Error(3): " . $pde->getMessage());
            die("modules.auth.login.php(3) - Database access error");
        }

        foreach ($rows[0] as $key => $value) {
            $authNamespace->$key = $value;
        }

        if (isset($authNamespace->role_name) &&
            $authNamespace->role_name == 'customer' &&
            $authNamespace->user_id > 0) {
            header('Location: index.php?module=customers&view=details&action=view&id='.$authNamespace->user_id);
        } else {
            header('Location: .');
        }
    } else {
        $errorMessage = STD_LOGIN_FAILED_MSG;
	}
}
// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage",$errorMessage);
