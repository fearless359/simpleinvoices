<?php

use Inc\Claz\PaymentWarehouse;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
Util::directAccessAllowed();

$rows = PaymentWarehouse::manageTableInfo();

$data = json_encode(['data' => mb_convert_encoding($rows, 'UTF-8')]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("payment_warehouse manage.php - Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($rows));

$smarty->assign('pageActive'  , 'paymentWarehouse');
$smarty->assign('activeTab'  , '#money');
