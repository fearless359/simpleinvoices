<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: save.php
 *   Custom fields save page
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2018-09-22 Richard Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $LANG, $pdoDb, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::isAccessAllowed();

// Deal with op and add some basic sanity checking
$display_block = "<div class=\"si_message_error\">{$LANG['save_custom_field_failure']}</div>";
$refresh_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : null;

if ($op === 'edit_custom_field') {
    if (isset($_POST['save_custom_field'])) {
        $clear_field = false;
        $error_found = false;
        // Check to see if the option to clear the value of the custom field in
        // the associated table. This can only happen if the field was changed
        // from non-blank to blank and the check box set on the custom field
        // maintenance screen.
        if (isset($_POST['clear_data']) && $_POST['clear_data'] == "yes") {
            // There is logic on the screen that prevents the clear data field from
            // being set when the associated custom field label is not blank. However,
            // we will still verify this here just to make sure something isn't
            // mistakenly changed that allows the clear condition to be set when it shouldn't.
            if (empty($_POST['cf_custom_label'])) {
                $clear_field = true;
            } else {
                $display_block ="<div class=\"si_message_warning\">{$LANG['clear_data']} field setting is invalid. No update performed.</div>";
                $error_found = true;
                error_log("modules/custom_fields/save.php - Clear Date set when label not empty.");
                error_log("Custom Field[" . $_POST['cr_custom_field'] . "] Label[" . $_POST['cf_custom_label'] . "]");
            }
        }

        if (!$error_found) {
            $result = array();
            try {
                $pdoDb->addSimpleWhere('cf_id', $_GET['id'], 'AND');
                $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                $pdoDb->setFauxPost(array("cf_custom_label" => $_POST['cf_custom_label']));

                $result = $pdoDb->request('UPDATE', 'custom_fields');
            } catch (PdoDbException $pde) {
                error_log("modules/custom_fields/save.php - error: " . $pde->getMessage());
            }

            if (!empty($result)) {
                if ($clear_field) {
                    // Split the value of the field name into parts and use that data to build
                    // the sql statement to clear the field in the associated table.
                    // EX: Field name is: customer_cf2. The split values are "customer" and "cf2".
                    //     The test for a missing "s" will cause the table name to be "customers".
                    //     The field name will be the constant, "custom_field", with the field number
                    //     from the end of "cf2" to be appended resulting in "custom_field2".
                    $parts = explode("_", $_POST['cf_custom_field']);
                    if (count($parts) == 2 && preg_match("/cf[1-4]/", $parts[1])) {
                        // The table name part of cf_custom_field doesn't contain the needed "s" except for biller.
                        $table = $parts[0] . (preg_match("/^(customer|product|invoice)$/", $parts[0]) ? 's' : '');
                        $field = "custom_field" . substr($parts[1], 2, 1);
                        $result = false;
                        try {
                            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
                            $pdoDb->setFauxPost(array($field => ''));
                            $result = $pdoDb->request('UPDATE', $table);
                        } catch (PdoDbException $pde) {
                            error_log("modules/custom_fields/save.php - error: " . $pde->getMessage());
                            $error_found = true;
                        }
                    }
                }
                $display_block = "<div class=\"si_message_ok\">{$LANG['save_custom_field_success']}</div>";
            }
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'custom_field');
$smarty->assign('active_tab', '#setting');
