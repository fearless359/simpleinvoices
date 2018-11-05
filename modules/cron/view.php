<?php

use Inc\Claz\Cron;
use Inc\Claz\DomainId;

global $smarty;

$saved = "false";
if (isset($_POST['op']) && $_POST['op'] =='edit' && !empty($_POST['invoice_id'])) {
    try {
        if (Cron::insert() > 0) $saved = "true";
    } catch (PDOException $pdo) {
        error_log("cron view.php - unable to insert record");
    }
}

$cron = Cron::select();

$smarty->assign('cron'     , $cron);
$smarty->assign('saved'    , $saved);
$smarty->assign("domain_id", DomainId::get());

$smarty->assign('pageActive', 'cron');

$smarty->assign('subPageActive', 'cron_view');
$smarty->assign('active_tab'   , '#money');

