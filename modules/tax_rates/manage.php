<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$taxes = Taxes::manageTableInfo();

$data = json_encode(['data' => $taxes]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($taxes));

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('activeTab', '#setting');
