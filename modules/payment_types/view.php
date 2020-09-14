<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('paymentType', PaymentType::getOne($_GET['id']));

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('subPageActive', "payment_types_view");
$smarty->assign('active_tab', '#setting');
