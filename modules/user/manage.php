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
global $LANG, $smarty;

$users = User::manageTableInfo();
$data = json_encode(['data' => $users]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign("numberOfRows", count($users));

$smarty->assign('pageActive', 'user');
$smarty->assign('activeTab', '#people');
