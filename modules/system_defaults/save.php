<?php

use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $LANG, $smarty;

Util::directAccessAllowed();

$displayBlock = "<div class='si_message_error'>$LANG[save_defaults_failure]</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=system_defaults&amp;view=manage' />";

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
    if (SystemDefaults::updateDefault($_POST['name'], $_POST['value'])) {
        $displayBlock = "<div class='si_message_ok'>$LANG[save_defaults_success]</div>";
    }
}

$smarty->assign('display_block',$displayBlock);
$smarty->assign('refresh_redirect',$refreshRedirect);

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');

