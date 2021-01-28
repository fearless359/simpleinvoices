<?php

use Inc\Claz\Index;
use Inc\Claz\Invoice;
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
        ['id' => DISABLED, 'status' => $LANG['draft']],
        ['id' => ENABLED, 'status' => $LANG['real']]
    ];
    $smarty->assign('status', $status);

    $preference = Preferences::getOne($_GET['id']);
    $indexGroup = $preference['index_group'];

    $smarty->assign('preference', $preference);
    $smarty->assign('defaults', SystemDefaults::loadValues());
    $smarty->assign('indexGroup', $indexGroup);

    $indexInfo = Preferences::getPreferencesWithIndexDefined($indexGroup);
    $smarty->assign('indexInfo', $indexInfo);

    // Allow this group to have an index setting created for it
    // if one doesn't exist already.
    $useThisPref = true;
    foreach ($indexInfo as $ii) {
        if ($ii['pref_id'] == $preference['pref_id']) {
            $useThisPref = false;
            break;
        }
    }
    $smarty->assign('useThisPref', $useThisPref);

    // If $useThisPref is true, then check to see if invoices for this preference
    // exist already. If they do, the starting number for this preference must be greater
    // than the greatest number already assigned.
    $startingId = Invoice::maxIndexIdForPreference($preference['pref_id']) + 1;
    $smarty->assign('startingId', $startingId);
    $smarty->assign('nextId', Index::next('invoice', $indexGroup));

    $smarty->assign('localeList', Util::getLocaleList());

    $smarty->assign('pageActive', 'preference');
    $smarty->assign('subPageActive', "preferences_edit");
    $smarty->assign('activeTab', '#setting');
}
