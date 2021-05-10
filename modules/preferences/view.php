<?php

use Inc\Claz\Index;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$status = [
    ['id'=>'0','status'=>$LANG['draft']],
    ['id'=>'1','status'=>$LANG['real']]
];
$smarty->assign('status',$status);

$preference = Preferences::getOne($_GET['id']);

$smarty->assign('preference',$preference);
$smarty->assign('defaults', SystemDefaults::loadValues());

$indexGroup = $preference['index_group'];
$smarty->assign('indexGroup', Preferences::getOne($indexGroup));
$smarty->assign('nextId', Index::next('invoice', $indexGroup));

$smarty->assign('preferences', Preferences::getActivePreferences());
$smarty->assign('localeList', Util::getLocaleList());

$smarty->assign('pageActive', 'invPrefs');
$smarty->assign('subPageActive', "invPrefsView");
$smarty->assign('activeTab', '#settings');

