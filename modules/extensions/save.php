<?php

use Inc\Claz\Extensions;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=extensions&amp;view=manage" />';
$display_block = "<div class=\"si_message_error\">{$LANG['failure']}</div>";

if ($_POST['action'] == "register") {
    if (Extensions::insert() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
    }
} else if ($_POST['action'] == "unregister") {
    $extension_id = $_POST['id'];
    if (Extensions::delete($extension_id)) {
        if (SystemDefaults::delete($extension_id)) {
            $display_block = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);


