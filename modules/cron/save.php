<?php

use Inc\Claz\Cron;
use Inc\Claz\Util;

/*
 *  Script: save.php
 *      Cron save page
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2018-12-11
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$op = (empty($_POST['op']) ? "" : $_POST['op']);
$display_block = "<div class=\"si_message_error\">{$LANG['save_cron_failure']}</div>";
$refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=cron&amp;view=manage\" />";

if ( $op === 'add') {
    if (!empty(Cron::insert())) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_cron_success']}</div>";
    }
} elseif ($op === 'edit') {
    if (Cron::update($_GET['id'])) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_cron_success']}</div>";
    }
} elseif ($op === 'delete') {
    if (Cron::delete($_GET['id'])) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['cron_delete_success']}</div>";
    } else {
        $display_block = "<div class=\"si_message_error\">{$LANG['cron_delete_failure']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'cron');
$smarty->assign('active_tab', '#money');
