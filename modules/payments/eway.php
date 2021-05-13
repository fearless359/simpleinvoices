<?php

use Inc\Claz\Eway;
use Inc\Claz\Invoice;

global $smarty;

$saved = false;

try {
    if ($_POST ['op'] == 'add' && ! empty ( $_POST ['invoice_id'] )) {
        $invoice = Invoice::getOne($_POST ['invoice_id']);

        $ewayCheck = new Eway();
        $ewayCheck->invoice = $invoice;
        $ewayPreCheck = $ewayCheck->preCheck();

        if ($ewayPreCheck == 'true') {
            // do eway payment
            $eway = new Eway();
            $eway->invoice = $invoice;
            $saved = $eway->payment();
        } else {
            $saved = 'check_failed';
        }
    }

    $smarty->assign ( 'invoice_all', Invoice::getAll() );
} catch (Exception $exp) {
    exit("modules/payments/eway.php Unexpected error: {$exp->getMessage()}");
}

$smarty->assign ( 'saved', $saved );

$smarty->assign ( 'pageActive', 'payment' );
$smarty->assign ( 'subPageActive', 'paymentEway' );
$smarty->assign ( 'activeTab', '#money' );

