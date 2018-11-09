<?php

use Inc\Claz\User;

/*
 *  Script: save.tpl
 *      User save template
 *
 * Authors:
 *      Justin Kelly, Nicolas Ruflin,
 *      Rich Rowley
 *
 * Last edited:
 *      2018-09-24
 *
 * License:
 *   GPL v3 or above
 */
global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=user&amp;view=manage' />";
$display_block = "<div class='si_message_error'>{$LANG['save_user_failure']}</div>";

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? $_POST['op'] : NULL;
$ok = true;
$exclude_pwd = true;
if (!empty($_POST['password'])) {
    if (empty($_POST['confirm_password']) || $_POST['password'] != $_POST['confirm_password']) {
        $ok = false;
        $display_block = "<div class='si_message_error'>'Password and Confirm Password do not match.'</div>";
    } else {
        $exclude_pwd = false;
    }
}

if ($ok) {
    if (isset($_POST['user_id']) &&
        (preg_match('/^(customer|biller)$/', $_POST['currrole']))) $_POST['user_id']++;

    if ($op === 'insert_user') {
        if (User::insertUser() > 0) {
            $display_block = "<div class='si_message_ok'>{$LANG['save_user_success']}</div>";
        }
    } elseif ($op === 'edit_user' && isset($_POST['save_user'])) {
        if (User::updateUser($exclude_pwd)) {
            $display_block = "<div class='si_message_ok'>{$LANG['save_user_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive', 'user');
$smarty->assign('active_tab', '#people');
