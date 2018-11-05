<?php

use Inc\Claz\Customer;

/*
 *  Script: save.php
 *      Customers save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-07-27
 *
 *  License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 */
global $config, $smarty, $LANG;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$display_block = "<div class=\"si_message_error\">{$LANG['save_customer_failure']}</div>";
$refresh_total = "<meta http-equiv=\"refresh\" content=\"2;url=index.php?module=customers&amp;view=manage\" />";

$error = false;
// The field is only non-empty if the user entered a value.
// TODO: A proper entry and confirmation new credit card value.
$excludeCreditCardNumber = true;
if (!empty($_POST['credit_card_number'])) {
    try {
        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
        $excludeCreditCardNumber = false;
    } catch (Exception $e) {
        error_log("Unable to encrypt the credit card number. Error reported: " . $e->getMessage());
        $error = true;
    }
}

if (!$error) {
    if ($op === "insert_customer") {
        if (Customer::insertCustomer($excludeCreditCardNumber)) {
            $display_block = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
        }
    } else if ($op === 'edit_customer' && isset($_POST['save_customer'])) {
        if (Customer::updateCustomer($_GET['id'], $excludeCreditCardNumber)) {
            $display_block = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
