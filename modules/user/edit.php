<?php /** @noinspection DuplicatedCode */

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\User;
use Inc\Claz\UserSecurity;
use Inc\Claz\Util;

/*
 * Script: details.php
 *      User details page
 * Authors:
 *      Justin Kelly, Nicolas Ruflin,
 *      Rich Rowley
 *
 * Last edited:
 *      2018-09-24
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *  https://simpleinvoices.group
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

global $smarty, $LANG;

$user = User::getOne($_GET['id']);

$custInfo = Customer::getAll(['noTotals' => true]);
$billers = Biller::getAll();

if ($user['user_id'] == 0) {
    $userIdDesc = '0 - User';
} elseif ($user['role_name'] == 'customer') {
    $userIdDesc = $user['user_id'] . " - Undefined";
    foreach($custInfo as $custInf) {
        if ($custInf['id'] == $user['user_id']) {
            $userIdDesc = $user['user_id'] . " - " . $custInf['name'];
            break;
        }
    }
} else {
    $userIdDesc = $user['user_id'] . " - Undefined";
    foreach($billers as $biller) {
        if ($biller['id'] == $user['user_id']) {
            $userIdDesc = $user['user_id'] . " - " . $biller['name'];
            break;
        }
    }
}

// Serialize the arrays so they can be put on screen as hidden items.
$val = [];
foreach($custInfo as $cust) {
    $val[] = $cust['id'] . " - " . $cust['name'];
}
$cust = implode('~', $val);

$val = [];
foreach($billers as $biller) {
    $val[] = $biller['id'] . " - " . $biller['name'];
}
$bilr = implode('~', $val);

$smarty->assign('enabled_options', [
    0 => $LANG['disabled'],
    1 => $LANG['enabled']
]);

$smarty->assign('user_id_desc', $userIdDesc);
$smarty->assign('orig_role_name', $user['role_name']);
$smarty->assign('orig_user_id', $user['user_id']);

$smarty->assign('usernamePattern', User::$usernamePattern);
$smarty->assign("pwd_pattern", UserSecurity::buildPwdPattern());
$smarty->assign('user', $user);
$smarty->assign('roles', User::getUserRoles());
$smarty->assign('cust_info', $custInfo);
$smarty->assign('billers', $billers);
$smarty->assign('cust', $cust);
$smarty->assign('bilr', $bilr);

$smarty->assign('pageActive', 'user');

$smarty->assign('subPageActive', "user_edit");
$smarty->assign('active_tab', '#people');
