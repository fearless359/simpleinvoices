<?php
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::isAccessAllowed();

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('subPageActive', 'payment_types_add');
$smarty->assign('active_tab', '#setting');
