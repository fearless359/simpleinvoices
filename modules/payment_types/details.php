<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $LANG, $smarty;
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

// Get the invoice id
$payment_type_id = $_GET['id'];

$paymentType = PaymentType::getOne($payment_type_id);

$smarty->assign('paymentType',$paymentType);

$smarty -> assign('pageActive', 'payment_type');
$subPageActive = $_GET['action'] == "view"  ? "payment_types_view" :
                                              "payment_types_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
