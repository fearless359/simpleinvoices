<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
Util::directAccessAllowed();

$paymentTypes = PaymentType::manageTableInfo();

$data = json_encode(['data' => $paymentTypes]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($paymentTypes));

$smarty->assign('pageActive'  , 'payment_type');
$smarty->assign('activeTab'  , '#setting');
