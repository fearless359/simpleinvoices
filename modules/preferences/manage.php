<?php

use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$preferences = Preferences::getPreferences();

$smarty -> assign("preferences", $preferences);
$smarty -> assign('pageActive' , 'preference');
$smarty -> assign('active_tab' , '#setting');
