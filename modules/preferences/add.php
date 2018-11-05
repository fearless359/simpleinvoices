<?php

use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if (!empty($_POST['p_description'])) {
	include("modules/preferences/save.php");
}
$defaults = SystemDefaults::loadValues();
$preferences = Preferences::getActivePreferences();

$localelist = Zend_Locale::getLocaleList();

$smarty->assign('preferences',$preferences);
$smarty->assign('defaults',$defaults);
$smarty->assign('localelist',$localelist);

$smarty->assign('pageActive', 'preference');

$smarty->assign('subPageActive', 'preferences_add');
$smarty->assign('active_tab', '#setting');

