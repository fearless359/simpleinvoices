<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

global $smarty;

if (!empty($_POST['invoice_id'])) {
    include 'modules/cron/save.php';
} else {
    try {
        $smarty->assign('invoice_all', Invoice::getAll());
    } catch (PdoDbException $pde) {
        exit("modules/cron/add.php - Unexpected error: Error {$pde->getMessage()}");
    }
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cronCreate');
    $smarty->assign('activeTab', '#money');
}
