<?php

use Inc\Claz\Extensions;
use Inc\Claz\SystemDefaults;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=extensions&amp;view=manage" />';
$display_block = "<div class=\"si_message_error\">{$LANG['failure']}</div>";

if ($_POST['cancel'] == 'Cancel') {
    $display_block = "<div class=\"si_message_warning\">{$LANG['cancelled']}</div>";
} else if ($_POST['action'] == "register") {
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


