<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$tax = Taxes::getOne($_GET['id']);
$types = Taxes::getTaxTypes();

$smarty->assign("tax",$tax);
$smarty->assign("types",$types);
$smarty->assign('orig_description', $tax['tax_description']);

$subPageActive = (isset($_GET['action']) && $_GET['action'] != "view"  ? "tax_rates_edit" : "tax_rates_view");
$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#setting');
