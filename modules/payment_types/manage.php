<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::directAccessAllowed();

$paymentTypes = PaymentType::select_all();

$smarty->assign('paymentTypes', $paymentTypes);
$smarty->assign('pageActive'  , 'payment_type');
$smarty->assign('active_tab'  , '#setting');
