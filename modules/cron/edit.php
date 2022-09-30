<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

global $smarty;

$id = $_GET['id'] ?? 0;

$cron = Cron::getOne($_GET['id']);
$smarty->assign('cron', $cron);

$invoice = Invoice::getOne($cron['invoice_id']);
$domainId = $invoice['domain_id'];
$smarty->assign('invoiceType', $invoice['type_id']);

$smarty->assign('cronInvoiceItemsCount', count(Cron::getCronInvoiceItems($id, $domainId)));

try {
    $smarty->assign('invoice_all', Invoice::getAll());
} catch (PdoDbException $pde) {
    error_log("edit.php Invoice::getAll() exception: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cronEdit');
$smarty->assign('activeTab', '#money');
