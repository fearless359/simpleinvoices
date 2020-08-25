<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
Util::directAccessAllowed();

$paymentTypes = PaymentType::getAll();

$smarty->assign('payment_types', $paymentTypes);
$smarty->assign('number_of_rows', count($paymentTypes));

$smarty->assign('pageActive'  , 'payment_type');
$smarty->assign('active_tab'  , '#setting');
