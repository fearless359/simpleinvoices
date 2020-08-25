<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='add') {
    include 'modules/cron/save.php';
} else {
    try {
        $smarty->assign('invoice_all', Invoice::getAll());
    } catch (PdoDbException $pde) {
        exit("modules/cron/add.php - Unexpected error: Error {$pde->getMessage()}");
    }
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cron_add');
    $smarty->assign('active_tab', '#money');
}