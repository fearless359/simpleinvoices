<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\PaymentType;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty, $LANG, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// @formatter:off
try {
    if (isset($_GET['id'])) {
        $invoice = Invoice::getOne($_GET['id']);
        $smarty->assign("invoice", $invoice);
        $smarty->assign("biller"         , Biller::getOne($invoice['biller_id']));
        $smarty->assign("customer"       , Customer::getOne($invoice['customer_id']));
        $smarty->assign("invoice"        , $invoice);
    }

    if (isset($_GET['message'])) {
        $smarty->assign('message', $_GET['message']);
    }
    $invoiceAll = Invoice::getAllWithHavings("money_owed", "id", '', false, true);

    $smarty->assign('invoice_all'    , $invoiceAll);
    $smarty->assign("paymentTypes"   , PaymentType::getAll(true));
    $smarty->assign("defaults"       , SystemDefaults::loadValues());
    $smarty->assign("today"          , date("Y-m-d"));
} catch (PdoDbException $pde) {
    exit("modules/payments/process.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', "paymentProcess");
$smarty->assign('activeTab'   , '#money');
// @formatter:on
