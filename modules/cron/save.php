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
global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$op = empty($_POST['op']) ? "" : $_POST['op'];
$displayBlock = "<div class='si_message_error'>{$LANG['saveCronFailure']}</div>";
$refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=cron&amp;view=manage\" />";

if ( $op === 'create') {
    if (!empty(Cron::insert())) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveCronSuccess']}</div>";
    }
} elseif ($op === 'edit') {
    if (Cron::update($_GET['id'])) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveCronSuccess']}</div>";
    }
} elseif ($op === 'delete') {
    if (Cron::delete($_GET['id'])) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['cronDeleteSuccess']}</div>";
    } else {
        $displayBlock = "<div class='si_message_error'>{$LANG['cronDeleteFailure']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'cron');
$smarty->assign('activeTab', '#money');
