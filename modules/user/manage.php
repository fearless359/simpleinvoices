<?php
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

$number_of_rows  = User::count();
$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'user');
$smarty -> assign('active_tab', '#people');
