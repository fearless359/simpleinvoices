<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;

global $smarty;

if (!empty($_POST['invoice_id'])) {
    include 'modules/cron/save.php';
} else {
    try {
        $invoiceDisplayDays = 0;
        if (empty($_GET['all_invoices']) || $_GET['all_invoices'] != 1) {
            $invoiceDisplayDays = SystemDefaults::getInvoiceDisplayDays();
        }

        $smarty->assign("invoiceDisplayDays", $invoiceDisplayDays);
        $smarty->assign('invoice_all', Invoice::getAll('index_name', 'desc', $invoiceDisplayDays));
    } catch (PdoDbException $pde) {
        exit("modules/cron/add.php - Unexpected error: Error {$pde->getMessage()}");
    }
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cronCreate');
    $smarty->assign('activeTab', '#money');
}
