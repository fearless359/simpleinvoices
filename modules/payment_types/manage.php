<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
Util::isAccessAllowed();

$payment_types = PaymentType::getAll();

$smarty->assign('payment_types', $payment_types);
$smarty->assign('number_of_rows', count($payment_types));

$smarty->assign('pageActive'  , 'payment_type');
$smarty->assign('active_tab'  , '#setting');
