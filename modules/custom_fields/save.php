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
$displayBlock = "<div class='si_message_error'>{$LANG['saveCustomFieldFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";

$clearData = isset($_POST['clear_data']) && strtolower($_POST['clear_data']) == 'yes';

// Set function parameters so call will fail but not thrown an error.
$cfId = isset($_GET['id']) ? $_GET['id'] : 0;  // 0 is an invalid id
$cfLabel = isset($_POST['cfLabel']) ? $_POST['cfLabel'] : '';

if (!empty($_POST['op']) && $_POST['op'] === 'edit') {
    if (CustomFields::update($cfId, $cfLabel)) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveCustomFieldSuccess']}</div>";
        if ($clearData) {
            $cfField = isset($_POST['cf_custom_field']) ? $_POST['cf_custom_field'] : '';
            if (!CustomFields::clearFields($cfField)) {
                $displayBlock = "<div class='si_message_error'>{$LANG['saveCustomFieldFailure']}</div>";
            }
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'custom_field');
$smarty->assign('activeTab', '#setting');
