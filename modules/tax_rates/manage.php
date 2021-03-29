<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$taxes = Taxes::getAll();

$smarty->assign("taxes", $taxes);
$smarty->assign('numberOfRows', count($taxes));

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('activeTab', '#setting');
