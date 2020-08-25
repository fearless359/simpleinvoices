<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

global $smarty;

if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    include 'modules/cron/save.php';
} else {
    try {
        $smarty->assign('invoice_all', Invoice::getAll('id', 'asc'));
    } catch (PdoDbException $pde) {
        exit("modules/cron/details.php Unexpected error: {$pde->getMessage()}");
    }
    $smarty->assign('cron', Cron::getOne($_GET['id']));

    $smarty->assign('pageActive', 'cron');
    $smarty->assign('subPageActive', 'cron_edit');
    $smarty->assign('active_tab', '#money');
}
