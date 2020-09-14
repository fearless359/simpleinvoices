<?php

use Inc\Claz\Inventory;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = $_POST['op'];

$displayBlock = "<div class='si_message_error'>{$LANG['save_inventory_failure']}</div>";
$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />';

if ($op === 'create') {
    if (Inventory::insert() > 0) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_inventory_success']}</div>";
    }
} elseif ($op === 'edit') {
    if (Inventory::update()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['save_inventory_success']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'inventory');
$smarty->assign('active_tab', '#product');
