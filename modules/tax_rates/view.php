<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$tax = Taxes::getOne($_GET['id']);

$smarty->assign("tax",$tax);
$smarty->assign("types", Taxes::getTaxTypes());
$smarty->assign('orig_description', $tax['tax_description']);

$smarty->assign('pageActive', 'taxRates');
$smarty->assign('subPageActive', "taxRatesView");
$smarty->assign('activeTab', '#settings');
