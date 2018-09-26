<?php
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

checkLogin();

$roles = User::getUserRoles();

if (!empty($_POST['username'])) {
    include ("modules/user/save.php");
}

$smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());

$smarty->assign('roles', $roles);

$smarty->assign('pageActive', 'user');
$smarty->assign('subPageActive', 'user_add');
$smarty->assign('active_tab', '#people');
