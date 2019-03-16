<?php

use Inc\Claz\CustomFields;
use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: details.php
 *     Custom fields details page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group */
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//get the invoice id
$cf_id = $_GET["id"];

$rows = array();
try {
    $pdoDb->addSimpleWhere('cf_id', $cf_id, 'AND');
    $pdoDb->addSimpleWhere('domain_id', DomainId::get());
    $rows = $pdoDb->request('SELECT', 'custom_fields');
} catch (PdoDbException $pde) {
    error_log("modules/custom_fields/details.php - error: " . $pde->getMessage());
}

if (empty($rows)) {
    $cf = array();
} else {
    $cf = $rows[0];
    $cf['name'] = CustomFields::getCustomFieldName($cf['cf_custom_field']);
}

$subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;

$smarty->assign('pageActive', "options");
$smarty->assign("cf",$cf);

$smarty->assign('pageActive'   , 'custom_field');
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab'   , '#setting');
