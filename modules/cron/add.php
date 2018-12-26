<?php

use Inc\Claz\DomainId;
use Inc\Claz\Invoice;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='add') {
    include 'modules/cron/save.php';
} else {
    $invoices = Invoice::getAll();
    $smarty->assign('invoice_all', $invoices);
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cron_add');
    $smarty->assign('active_tab', '#money');
}