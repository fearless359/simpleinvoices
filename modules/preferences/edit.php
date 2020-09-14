<?php

use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['p_description'])) {
    include "modules/preferences/save.php";
} else {
    $status = [
        ['id' => '0', 'status' => $LANG['draft']],
        ['id' => '1', 'status' => $LANG['real']]
    ];
    $smarty->assign('status', $status);

    $preference = Preferences::getOne($_GET['id']);

    $smarty->assign('preference', $preference);
    $smarty->assign('defaults', SystemDefaults::loadValues());
    $smarty->assign('index_group', Preferences::getOne($preference['index_group']));
    $smarty->assign('preferences', Preferences::getActivePreferences());
    $smarty->assign('localeList', Util::getLocaleList());

    $smarty->assign('pageActive', 'preference');
    $smarty->assign('subPageActive', "preferences_edit");
    $smarty->assign('active_tab', '#setting');
}
