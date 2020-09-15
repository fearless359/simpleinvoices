<?php

use Inc\Claz\PdoDbException;
use Inc\Claz\User;
use Inc\Claz\Util;

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
Util::directAccessAllowed();

$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=user&amp;view=manage' />";
$displayBlock = "<div class='si_message_error'>{$LANG['save_user_failure']}</div>";

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? $_POST['op'] : NULL;
$ok = true;
$excludePwd = true;
if (!empty($_POST['password'])) {
    if (empty($_POST['confirm_password']) || $_POST['password'] != $_POST['confirm_password']) {
        $ok = false;
        $displayBlock = "<div class='si_message_error'>'Password and Confirm Password do not match.'</div>";
    } else {
        $excludePwd = false;
    }
}

if ($ok) {
    if (isset($_POST['user_id']) &&
        preg_match('/^(customer|biller)$/', $_POST['currRole'])) {
        $_POST['user_id']++;
    }

    if ($op === 'create') {
        try {
            if (User::insertUser() > 0) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['save_user_success']}</div>";
            }
        } catch (PdoDbException $pde) {
            exit("modules/user/save.php Unexpected error: {$pde->getMessage()}");
        }
    } elseif ($op === 'edit' && isset($_POST['save_user'])) {
        if (User::updateUser($excludePwd)) {
            $displayBlock = "<div class='si_message_ok'>{$LANG['save_user_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'user');
$smarty->assign('active_tab', '#people');
