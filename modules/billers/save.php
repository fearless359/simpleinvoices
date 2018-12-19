<?php

use Inc\Claz\Biller;
use Inc\Claz\Util;

/*
 *  Script: save.php
 *      Biller save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-01-16 by Rich Rowley to add signature field
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$op = (empty($_POST['op']) ? "" : $_POST['op']);
$display_block = "<div class=\"si_message_error\">{$LANG['save_biller_failure']}</div>";
$refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=billers&amp;view=manage\" />";

if ( $op === 'add') {
    if (Biller::insertBiller() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_biller_success']}</div>";
    }
} else if ($op === 'edit') {
    if (Biller::updateBiller()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_biller_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
