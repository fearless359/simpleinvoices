<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['tax_description'])) {
    include("modules/tax_rates/save.php");
}
$types = Taxes::getTaxTypes();

$smarty->assign("types", $types);

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('subPageActive', 'tax_rate_add');
$smarty->assign('active_tab', '#setting');
