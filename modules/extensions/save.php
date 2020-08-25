<?php

use Inc\Claz\Extensions;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$refreshRedirect = '<meta http-equiv="refresh" content="2;URL=index.php?module=extensions&amp;view=manage" />';
$displayBlock = "<div class=\"si_message_error\">{$LANG['failure']}</div>";

if ($_POST['action'] == "register") {
    if (Extensions::insert() > 0) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
    }
} elseif ($_POST['action'] == "unregister") {
    $extensionId = $_POST['id'];
    if (Extensions::delete($extensionId)) {
        if (SystemDefaults::delete($extensionId)) {
            $displayBlock = "<div class=\"si_message_ok\">{$LANG['success']}</div>";
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);


