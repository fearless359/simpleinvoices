<?php

use Inc\Claz\Customer;
use Inc\Claz\Util;

/*
 *  Script: save.php
 *      Customers save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-10-03 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 */
global $config, $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? $_POST['op'] : null;

$displayBlock = "<div class=\"si_message_error\">{$LANG['save_customer_failure']}</div>";
$refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;url=index.php?module=customers&amp;view=manage\" />";

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
    } catch (Exception $exp) {
        error_log("Unable to encrypt the credit card number. Error reported: " . $exp->getMessage());
        $error = true;
    }
}

if (!$error) {
    if ($op === "add") {
        if (Customer::insertCustomer($excludeCreditCardNumber)) {
            $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
        }
    } elseif ($op === 'edit') {
        if (Customer::updateCustomer($_GET['id'], $excludeCreditCardNumber)) {
            $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
        }
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
