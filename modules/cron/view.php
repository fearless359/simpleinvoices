<?php

use Inc\Claz\Cron;

global $smarty;

$cron = Cron::getOne($_GET['id']);

$smarty->assign('cron', $cron);

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cron_view');
$smarty->assign('activeTab', '#money');

