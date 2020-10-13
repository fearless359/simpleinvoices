<?php

use Inc\Claz\Cron;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Manage Invoices page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last Modified:
 *      2018-12-11 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$crons = Cron::getAll();

$smarty->assign('crons', $crons);
$smarty->assign("numberOfRows", count($crons));

$smarty->assign('pageActive', 'cron');
$smarty->assign('activeTab', '#money');
