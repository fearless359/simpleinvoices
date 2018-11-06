<?php

use Inc\Claz\Extensions;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$refresh_redirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=extensions&amp;view=manage" />';

$display_block = "<div class=\"si_message_error\">{$LANG['failure']}</div>";
if ($_POST['action'] == "register") {
    if (Extensions::insert()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
    }
} elseif ($_POST['action'] == "unregister") {
    $extension_id = $_POST['id'];
    if (Extensions::delete($extension_id)) {
        if (\Inc\Claz\SystemDefaults::delete($extension_id)) {
            $display_block = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
        }
    }
} else {
    $display_block = "<div class=\"si_message_warning\">{$LANG['cancelled']}</div>";
}

