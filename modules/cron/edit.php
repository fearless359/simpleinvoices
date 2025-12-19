<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;

global $smarty;

$id = $_GET['id'] ?? 0;

$cron = Cron::getOne($_GET['id']);
$smarty->assign('cron', $cron);

try {
    $invoiceDisplayDays = SystemDefaults::getInvoiceDisplayDays();

    $smarty->assign("invoiceDisplayDays", $invoiceDisplayDays);

    $assignedInvoice = Invoice::getOne($cron['invoice_id']);

    $domainId = $assignedInvoice['domain_id'];
    $smarty->assign('invoiceType', $assignedInvoice['type_id']);

    $smarty->assign('cronInvoiceItemsCount', count(Cron::getCronInvoiceItems($id, $domainId)));

    $invoices = Invoice::getAll('index_name', 'desc', $invoiceDisplayDays);
    $containsInvoice = false;
    foreach ($invoices as $invoice) {
        if ($invoice['id'] == $assignedInvoice['id']) {
            $containsInvoice = true;
            break;
        }
    }
    if (!$containsInvoice) {
        $invoices[] = $assignedInvoice;
    }

    $smarty->assign('invoice_all', $invoices);
} catch (PdoDbException $pde) {
    error_log("edit.php Invoice::getAll() exception: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cronEdit');
$smarty->assign('activeTab', '#money');
