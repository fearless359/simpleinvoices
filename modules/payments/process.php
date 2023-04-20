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

try {
    $errorExit = false;
    if (isset($_GET['id'])) {
        $invoice = Invoice::getOne($_GET['id']);
        if ($invoice['owing'] == 0) {
            // Payment not allowed on invoice with no amount owing
            $displayBlock = "<div class='si_message_error'>$LANG[paymentUc] $LANG[notLc] $LANG[allowed] $LANG[onLc] $LANG[invoice] " .
                "$LANG[with] $LANG[no] $LANG[amount] $LANG[owing].</div>";
            $refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";

            $smarty->assign('display_block', $displayBlock);
            $smarty->assign('refresh_redirect', $refreshRedirect);

            $smarty->assign('pageActive'   , 'invoice');
            $smarty->assign('activeTab'   , '#money');
            $errorExit = true;
        }
        $smarty->assign("invoice", $invoice);
        $smarty->assign("biller", Biller::getOne($invoice['biller_id']));
        $smarty->assign("customer", Customer::getOne($invoice['customer_id']));
    }

    if (isset($_GET['message'])) {
        $smarty->assign('message', $_GET['message']);
    }
    $invoiceAll = Invoice::getAllWithHavings("money_owed", "id", '', false, true);

    $smarty->assign('invoice_all', $invoiceAll);
    $smarty->assign("paymentTypes", PaymentType::getAll(true));
    $smarty->assign("defaults", SystemDefaults::loadValues());
    $smarty->assign("today", date("Y-m-d"));
} catch (PdoDbException $pde) {
    exit("modules/payments/process.php Unexpected error: {$pde->getMessage()}");
}

if (!$errorExit) {
    $smarty->assign('pageActive', 'payment');
    $smarty->assign('subPageActive', "paymentProcess");
    $smarty->assign('activeTab', '#money');
}
