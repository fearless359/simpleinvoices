<?php

use Inc\Claz\CronLog;
use Inc\Claz\DomainId;
use Inc\Claz\Util;

global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cronLogs = CronLog::getAll($pdoDb, DomainId::get());
$smarty->assign("cronLogs",$cronLogs);

$smarty->assign('pageActive', 'options');
$smarty->assign('activeTab', '#settings');
