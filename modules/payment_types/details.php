<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('paymentType', PaymentType::getOne($_GET['id']));

$smarty->assign('pageActive', 'payment_type');
$subPageActive = $_GET['action'] == "view"  ? "payment_types_view" :
                                              "payment_types_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#setting');
