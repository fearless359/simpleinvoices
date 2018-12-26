<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$taxes = Taxes::getAll();

$smarty->assign("taxes", $taxes);
$smarty->assign('number_of_rows', count($taxes));

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('active_tab', '#setting');
