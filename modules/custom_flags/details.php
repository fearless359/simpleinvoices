<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

if (empty($_GET['id'])) {
    $associated_table = $_GET['associated_table'];
    $flg_id = $_GET['flg_id'];
} else {
    $parts = explode(':', $_GET['id']);
    $associated_table = $parts[0];
    $flg_id = $parts[1];
}

$cflg = CustomFlags::getCustomFlag($associated_table, $flg_id);

$smarty->assign('cflg', $cflg);

$enable_options = array(DISABLED => 'Disabled', ENABLED => 'Enabled');
$smarty->assign('enable_options', $enable_options);

$smarty->assign('pageActive', 'custom_flags');
$subPageActive = ($_GET['action'] == "view" ? "custom_flag_view" : "custom_flag_edit");
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#settings');

