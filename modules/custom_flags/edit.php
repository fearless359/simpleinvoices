<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// If no id specified, the associated_table and flg_id are present, so use them.
// If id is specified, it contains both the associated_table and flg_id. So explode
// them to get these fields.
if (empty($_GET['id'])) {
    $associatedTable = $_GET['associated_table'];
    $flgId = $_GET['flg_id'];
} else {
    $parts = explode(':', $_GET['id']);
    $associatedTable = $parts[0];
    $flgId = $parts[1];
}

$smarty->assign('cflg', CustomFlags::getOne($associatedTable, $flgId));

$smarty->assign('enable_options', [DISABLED => 'Disabled', ENABLED => 'Enabled']);

$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('subPageActive', "custom_flag_edit");
$smarty->assign('activeTab', '#settings');

