<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

/*
 *  Script: details.php
 *      Custom fields save page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=custom_flags&amp;view=manage' />";

$op = empty($_POST['op']) ? '' : $_POST['op'];

$displayBlock = "<div class='si_message_error'>{$LANG['save_custom_flags_failure']}</div>";
if (isset($_POST['cancel'])) {
    $displayBlock = "<div class='si_message_warning'>{$LANG['cancelled']}</div>";
} elseif ($op === 'edit') {
    if (isset($_POST['save_custom_flag'])) {
        $flgId = intval($_POST['flg_id']);
        $clearField = isset($_POST["clear_custom_flags_{$flgId}"]) ? $_POST["clear_custom_flags_{$flgId}"] : DISABLED;
        if (CustomFlags::updateCustomFlags(
                $_POST["associated_table"],
                $flgId,
                $_POST["field_label"],
                $_POST["enabled"],
                $clearField,
                $_POST["field_help"])) {
            $displayBlock = "<div class='si_message_ok'>{$LANG['save_custom_flags_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('active_tab', '#settings');
