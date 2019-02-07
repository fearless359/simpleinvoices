<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;

global $smarty;

if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    include 'modules/cron/save.php';
} else {
    $invoice_all = Invoice::getAll('id');
    $cron = Cron::getOne($_GET['id']);

    $smarty->assign('invoice_all', $invoice_all);
    $smarty->assign('cron', $cron);

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cron_edit');
    $smarty->assign('active_tab', '#money');
}
