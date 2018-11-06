<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\OnClause;
use Inc\Claz\PdoDbException;

/*
 *  Script: delete.php
 *      Do the deletion of a cron record
 *
 *  Authors:
 *      Rich Rowley
 *
 *  Last edited:
 *      2016-08-09
 *
 *  License:
 *      GPL v3 or above
 * 
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

checkLogin();
$err_message = "";
$cron = "";
if ($_GET['stage'] == 2) {
    $smarty->assign("index_id", $_POST['index_id']);
    $saved = false;
    try {
        $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("domain_id", DomainId::get());
        if ($pdoDb->request("DELETE", "cron")) {
            $saved = "true";
        }
    } catch (PdoDbException $pde) {
        error_log("modules/cron/delete - error: " . $pde->getMessage());
    }

    if (!$saved) {
        $err_message = "Unable to delete the specified record.";
    }
    $stage = '0';
} else {
    $rows = array();
    try {
        $pdoDb->addSimpleWhere("cron.id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("cron.domain_id", DomainId::get());

        $oc = new OnClause();
        $oc->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "invoices", "iv", $oc));

        $pdoDb->setSelectList(array("cron.*", "iv.index_id"));
        $rows = $pdoDb->request("SELECT", "cron", "cron");
    } catch (PdoDbException $pde) {
        error_log("modules/cron/delete - error(2): " . $pde->getMessage());
    }
    if (empty($rows)) {
        $err_message = "Unable to find the requested record.";
    } else {
        $cron = $rows[0];
    }
    $saved = null;
    $stage = 1;
}

$smarty->assign('cron'       , $cron);
$smarty->assign('saved'      , $saved);
$smarty->assign('err_message', $err_message);
$smarty->assign('stage'      , $stage);

$smarty->assign('pageActive'   , 'cron');
$smarty->assign('subPageActive', 'cron_manage');
$smarty->assign('active_tab'   , '#money');
