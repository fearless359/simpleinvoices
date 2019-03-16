<?php

use Inc\Claz\CustomFields;
use Inc\Claz\Util;

/*
 *  Script: save.php
 *      Custom fields save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-12-16 Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */

global $LANG, $pdoDb, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$display_block = "<div class=\"si_message_error\">{$LANG['save_custom_field_failure']}</div>";
$refresh_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";

$clear_data = (isset($_POST['clear_data']) && strtolower($_POST['clear_data']) == 'yes');

// Set function parameters so call will fail but not thrown an error.
$cf_id = (isset($_GET['id']) ? $_GET['id'] : 0);  // 0 is an invalid id
$cf_label = (isset($_POST['cf_custom_label']) ? $_POST['cf_custom_label'] : '');

$op = (!empty($_POST['op']) ? $_POST['op'] : null);
if ($op === 'edit') {
    if (CustomFields::update($cf_id, $cf_label)) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_custom_field_success']}</div>";
        if ($clear_data) {
            $cf_field = (isset($_POST['cf_custom_field']) ? $_POST['cf_custom_field'] : '');
            if (!CustomFields::clearFields($cf_field)) {
                $display_block = "<div class=\"si_message_error\">{$LANG['save_custom_field_failure']}</div>";
            }
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'custom_field');
$smarty->assign('active_tab', '#setting');
