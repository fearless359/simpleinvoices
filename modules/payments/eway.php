<?php

use Inc\Claz\Eway;
use Inc\Claz\Invoice;

global $smarty;

$saved = false;

$invoice_all = Invoice::getAll();

if (($_POST ['op'] == 'add') && (! empty ( $_POST ['invoice_id'] ))) {
    $invoice = Invoice::select ( $_POST ['invoice_id'] );

    $eway_check = new Eway ();
    $eway_check->invoice = $invoice;
    $eway_pre_check = $eway_check->pre_check ();

    if ($eway_pre_check == 'true') {
        // do eway payment
        $eway = new Eway ();
        $eway->invoice = $invoice;
        $saved = $eway->payment ();
    } else {
        $saved = 'check_failed';
    }
}

$smarty->assign ( 'invoice_all', $invoice_all );
$smarty->assign ( 'saved', $saved );

$smarty->assign ( 'pageActive', 'payment' );
$smarty->assign ( 'subPageActive', 'payment_eway' );
$smarty->assign ( 'active_tab', '#money' );

