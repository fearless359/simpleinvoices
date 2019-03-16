<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\PaymentType;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty, $LANG, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$paymentTypes = PaymentType::getAll(true);
$chk_pt = 0;
foreach ($paymentTypes as $paymentType) {
    if (preg_match('/^check$/iD', $paymentType['pt_description'])) {
        $chk_pt = trim($paymentType['pt_id']);
        break;
    }
}

$today = date("Y-m-d");

if(isset($_GET['id'])) {
    $invoice = Invoice::getOne($_GET['id']);
error_log("invoice - " . print_r($invoice, true));
} else {
    $rows = Invoice::getAll();
    $invoice = (empty($rows) ? $rows : $rows[0]);
}

// @formatter:off
$customer = Customer::getOne($invoice['customer_id']);
$biller   = Biller::getOne($invoice['biller_id']);
$defaults = SystemDefaults::loadValues();

$invoice_all = Invoice::getAllWithHavings("money_owed", "id");

$smarty->assign('invoice_all',$invoice_all);

$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', "payment_process");
$smarty->assign('active_tab'   , '#money');
// @formatter:on
