<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('paymentType', PaymentType::getOne($_GET['id']));

$smarty->assign('pageActive', 'pymtTypes');
$smarty->assign('subPageActive', "pymtTypesView");
$smarty->assign('activeTab', '#settings');
