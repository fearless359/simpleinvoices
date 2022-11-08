<?php

use Inc\Claz\PaymentWarehouse;
use Inc\Claz\Util;

global $smarty;
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('paymentWarehouse', PaymentWarehouse::getOne($_GET['id'], 0));

$smarty->assign('pageActive', 'paymentWarehouse');
$smarty->assign('subPageActive', "view");
$smarty->assign('activeTab', '#money');
