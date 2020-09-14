<?php

use Inc\Claz\Cron;
use Inc\Claz\Util;

/*
 *  Script: delete.php
 *      Delete a cron record
 *
 *  Authors:
 *      Rich Rowley
 *
 *  Last edited:
 *      2018-12-11
 *
 *  License:
 *      GPL v3 or above
 * 
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

Util::directAccessAllowed();

$smarty->assign('cron', Cron::getOne($_GET['id']));

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cron_manage');
$smarty->assign('active_tab', '#money');
