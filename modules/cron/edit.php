<?php

use Inc\Claz\Cron;
use Inc\Claz\Invoice;

global $smarty;

$smarty->assign('cron', Cron::getOne($_GET['id']));
$smarty->assign('invoice_all', Invoice::getAll());

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cron_view');
$smarty->assign('activeTab', '#money');
