<?php

use Inc\Claz\Inventory;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = !empty($_POST ['op']) ? $_POST['op'] : null;

$display_block = "<div class='si_message_error'>{$LANG['save_inventory_failure']}</div>";
$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />';

if ($op === 'add') {
    if (Inventory::insert() > 0) {
        $display_block = "<div class='si_message_ok'>{$LANG['save_inventory_success']}</div>";
    }
} else if ($op === 'edit') {
    if (Inventory::update()) {
        $display_block = "<div class='si_message_ok'>{$LANG['save_inventory_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign( 'pageActive', 'inventory' );
$smarty->assign( 'active_tab', '#product' );
