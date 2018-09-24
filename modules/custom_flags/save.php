<?php
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
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$display_class = "si_message_warning";
$display_message = $LANG['cancelled'];
$refresh_redirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=custom_flags&amp;view=manage' />";

$op = empty($_POST['op']) ? '' : $_POST['op'];

if ($op === 'edit_custom_flag') {
    if (isset($_POST['save_custom_flag'])) {
        $flg_id = $_POST['flg_id'];
        try {
            CustomFlags::updateCustomFlags(
                $_POST["associated_table"],
                intval($flg_id),
                $_POST["field_label"],
                $_POST["enabled"],
                (isset($_POST["clear_custom_flags_$flg_id"]) ? $_POST["clear_custom_flags_$flg_id"] : DISABLED),
                $_POST["field_help"]);
            $display_class = "si_message_ok";
            $display_message = $LANG['save_custom_field_success'];
        } catch (PdoDbException $pdo) {
            $display_class = "si_message_error";
            $display_message = $LANG['save_custom_flags_failure'];
        }
    }
}

$smarty->assign('display_class', $display_class);
$smarty->assign('display_message', $display_message);
$smarty->assign('refresh_redirect', $refresh_redirect);
$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('active_tab', '#settings');
