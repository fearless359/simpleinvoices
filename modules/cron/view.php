<?php

use Inc\Claz\Cron;

global $smarty;

$saved = "false";
if (isset($_POST['op']) && $_POST['op'] == 'edit' && !empty($_POST['invoice_id'])) {
    if (Cron::insert() > 0) $saved = "true";
}

$cron = Cron::getOne($_GET['id']);

$smarty->assign('cron', $cron);
$smarty->assign('saved', $saved);

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cron_view');
$smarty->assign('active_tab', '#money');

