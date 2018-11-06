<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

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
checkLogin();

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

if (empty(rows)) {
    $cf = array();
} else {
    $cf = $rows[0];
    $cf['name'] = get_custom_field_name($cf['cf_custom_field']);
}

//$print_product = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE cf_id = :id AND domain_id = :domain_id";
//$sth = dbQuery($print_product, ':id', $cf_id, ':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
//$cf = $sth->fetch();
//$cf['name'] = get_custom_field_name($cf['cf_custom_field']);

$subPageActive = $_GET['action'] =="view"  ? "custom_fields_view" : "custom_fields_edit" ;

$smarty->assign('pageActive', "options");
$smarty->assign("cf",$cf);

$smarty->assign('pageActive'   , 'custom_field');
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab'   , '#setting');
