<?php

use Inc\Claz\Cron;

global $smarty;

$cron = Cron::getOne($_GET['id']);

$smarty->assign('cron', $cron);

$smarty->assign('pageActive', 'cron');
$smarty->assign('subPageActive', 'cronView');
$smarty->assign('activeTab', '#money');

