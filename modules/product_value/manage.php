<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$number_of_rows= ProductValues::count();
$smarty->assign("number_of_rows",$number_of_rows);

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');
