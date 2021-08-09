<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;

global $smarty;

$smarty->assign('cron', Cron::getOne($_GET['id']));
try {
    $smarty->assign('invoice_all', Invoice::getAll());
} catch (PdoDbException $pde) {
    error_log("edit.php Invoice::getAll() exception: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cronEdit');
$smarty->assign('activeTab', '#money');
