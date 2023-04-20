<?php

use Inc\Claz\Customer;
use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::directAccessAllowed();

if (!empty($_POST['customer'])) {
    include 'modules/payment_warehouse/save.php';
} else {
    if (isset($_POST['op'])) {
        $smarty->assign('message', "");
    }

    $customers = Customer::getALL([
        'enabledOnly'    => true,
        'noTotals'       => true,
        'notInWarehouse' => true
    ]);

    $smarty->assign('customers', $customers);
    $smarty->assign('customerCount', count($customers));

    $smarty->assign('paymentTypes', PaymentType::getAll(true));

    $smarty->assign('pageActive', 'paymentWarehouse');
    $smarty->assign('subPageActive', 'create');
    $smarty->assign('activeTab', '#money');
}
