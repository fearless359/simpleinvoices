<?php

use Inc\Claz\CustomersPastDue;
use Inc\Claz\Util;

/*
 * Script: reportPastDue.php collecting past due information.
 * Author: Richard Rowley
 */
global $smarty;

Util::directAccessAllowed();

$grandTotalBilled = 0.0;
$grandTotalPaid = 0.0;
$grandTotalOwed = 0.0;

$custInfo = CustomersPastDue::getCustInfo($grandTotalBilled, $grandTotalPaid, $grandTotalOwed);

$smarty->assign('custInfo', $custInfo);
$smarty->assign('grandTotalBilled', $grandTotalBilled);
$smarty->assign('grandTotalPaid', $grandTotalPaid);
$smarty->assign('grandTotalOwed', $grandTotalOwed);
