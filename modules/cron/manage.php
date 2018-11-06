<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

/*
 *  Script: manage.php
 *      Manage Invoices page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last Modified:
 *      2016-08-07
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$rows = array();
try {
    $pdoDb->addSimpleWhere("domain_id", DomainId::get());
    $pdoDb->addToFunctions("COUNT(*) as count");
    $rows = $pdoDb->request("SELECT", "cron");
} catch (PdoDbException $pde) {
    error_log("modules/cron/manage.php - error: " . $pde->getMessage());
}

$number_of_crons = 0;
if (!empty($rows)) {
    $number_of_crons = $rows[0]['count'];
}


$smarty->assign("number_of_crons", $number_of_crons);

$smarty->assign('pageActive', 'cron');
$smarty->assign('active_tab', '#money');

$url =  'index.php?module=cron&view=xml';
$smarty->assign('url', $url);
