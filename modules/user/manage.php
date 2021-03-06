<?php

use Inc\Claz\User;

/*
 *  Script: manage.php
 *      Manage users page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last updated:
 *      2018-09-25 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

$users = array();
$rows = User::getAll();
foreach ($rows as $row) {
    $row['vname'] = $LANG['view'] . ' ' . $row['username'];
    $row['ename'] = $LANG['edit'] . ' ' . $row['username'];
    $row['image'] = ($row['enabled'] == ENABLED ? 'images/common/tick.png' : 'images/common/cross.png');
    $users[] = $row;
}

$smarty->assign('users', $users);
$smarty->assign("number_of_rows", count($users));

$smarty->assign('pageActive', 'user');
$smarty->assign('active_tab', '#people');
