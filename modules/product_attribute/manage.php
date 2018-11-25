<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$rows = ProductAttributes::getAll();
$number_of_rows = count($rows);
$smarty->assign("number_of_rows",$number_of_rows);

$pageActive = "product_attribute_manage";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');
