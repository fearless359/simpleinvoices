<?php

use Inc\Claz\SystemDefaults;

global $smarty;

checkLogin();

$display_block = "<div class='si_message_error'>$LANG[save_defaults_failure]</div>";
$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=system_defaults&amp;view=manage' />";

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
    if (SystemDefaults::updateDefault($_POST['name'], $_POST['value'])) {
        $display_block = "<div class='si_message_ok'>$LANG[save_defaults_success]</div>";
    }
}

$smarty -> assign('display_block',$display_block);
$smarty -> assign('refresh_total',$refresh_total);

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');

