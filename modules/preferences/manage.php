<?php

use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$preferences = Preferences::getAll();

$smarty->assign("preferences", $preferences);
$smarty->assign('number_of_rows', count($preferences));

$smarty->assign('pageActive', 'preference');
$smarty->assign('activeTab', '#setting');
