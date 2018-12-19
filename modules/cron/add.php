<?php

use Inc\Claz\DomainId;
use Inc\Claz\DynamicJs;
use Inc\Claz\Invoice;

global $smarty;

if (!empty($_POST['op']) && $_POST['op'] =='add') {
    include 'modules/cron/save.php';
} else {
    DynamicJs::begin();
    DynamicJs::formValidationBegin("frmpost");
    DynamicJs::validateRequired("invoice_id",$LANG['invoice_id']);
    DynamicJs::validateRequired("recurrence",$LANG['recurrence']);
    DynamicJs::validateRequired("start_date",$LANG['start_date']);
    DynamicJs::formValidationEnd();
    DynamicJs::end();

    $invoices = Invoice::selectAll();

    $smarty->assign('invoice_all', $invoices);
    $smarty->assign("domain_id", DomainId::get());

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cron_add');
    $smarty->assign('active_tab', '#money');
}