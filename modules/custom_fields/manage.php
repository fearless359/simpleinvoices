<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

/*
 * Script: manage.php
 *     Custom fields manage page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$rows = array();
try {
    $pdoDb->addSimpleWhere('domain_id', DomainId::get());
    $pdoDb->setOrderBy('cf_custom_field');
    $pdoDb->setSelectAll(true);
    $rows = $pdoDb->request('SELECT', 'custom_fields');
} catch (PdoDbException $pde) {
    error_log("modules/custom_fields/manager.php - error: " . $pde->getMessage());
}

$cfs = array();
foreach ($rows as $row) {
    $row['field_name'] = get_custom_field_name($row['cf_custom_field']);
    $cfs[] = $row;
}

$smarty -> assign("cfs",$cfs);

$smarty -> assign('pageActive', 'custom_field');
$smarty -> assign('active_tab', '#setting');
