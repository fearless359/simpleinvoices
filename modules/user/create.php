<?php

use Inc\Claz\User;
use Inc\Claz\UserSecurity;
use Inc\Claz\Util;

/*
 * Script: add.php
 *      User add page
 *
 * Authors:
 *      Justin Kelly, Nicolas Ruflin,
 *      Rich Rowley
 *
 * Last edited:
 *      2018-09-24
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $smarty;

Util::directAccessAllowed();

if (!empty($_POST['username'])) {
    include "modules/user/save.php";
} else {
    $smarty->assign('usernamePattern', User::$usernamePattern);
    $smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());

    $smarty->assign('pageActive', 'user');
    $smarty->assign('subPageActive', 'userCreate');
    $smarty->assign('activeTab', '#people');
}
