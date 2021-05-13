<?php

use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['p_description'])) {
	include "modules/preferences/save.php";
} else {
    $smarty->assign('indexInfo', Preferences::getPreferencesWithIndexDefined());
    $smarty->assign('defaults', SystemDefaults::loadValues());
    $smarty->assign('localeList', Util::getLocaleList());

    $smarty->assign('pageActive', 'invPrefs');
    $smarty->assign('subPageActive', 'invPrefsCreate');
    $smarty->assign('activeTab', '#settings');
}
