<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty -> assign("taxes", Taxes::getTaxes());

$smarty -> assign('pageActive', 'tax_rate');
$smarty -> assign('active_tab', '#setting');
