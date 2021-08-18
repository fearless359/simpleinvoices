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
$op = $_POST['op'] ?? null;
$id = $_GET['id'] ?? null;
$errorMsg = '';

$displayBlock = "<div class='si_message_error'>{$LANG['saveCustomerFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=customers&amp;view=manage' />";

$allDone = false;
$error = false;
// The field is non-empty if the user entered a value.
$excludeCreditCardNumber = false;

if (empty($_POST['credit_card_number'])) {
    // Check to see if there was a credit card number before this submission.
    if (!empty($_POST['origCcMaskedValue'])) {
        // The credit card number is blank but the mask indicates there is a credit card
        // present. If the associated fields contain values, exclude the credit card number
        // field from the update, so it isn't cleared. Otherwise, leave it and let it be
        // cleared along with the other fields.
        if (!empty($_POST['credit_card_holder_name']) ||
            !empty($_POST['credit_card_expiry_month']) ||
            !empty($_POST['credit_card_expiry_year'])) {
            $excludeCreditCardNumber = true;
        }
    } elseif (!empty($_POST['credit_card_holder_name']) ||
              !empty($_POST['credit_card_expiry_month']) ||
              !empty($_POST['credit_card_expiry_year'])) {
        $errorMsg = $LANG['creditCardNumberRequired'];
        $allDone = true;
        $refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=customers&amp;view=$op&amp;id=$id&amp;errorMsg=$errorMsg' />";
    }
} else {
    try {
        $key = $config['encryptionDefaultKey'];
        $enc = new Encryption();
        $_POST['credit_card_number'] = $enc->encrypt($key, $_POST['credit_card_number']);
    } catch (Exception $exp) {
        error_log("Unable to encrypt the credit card number. Error reported: " . $exp->getMessage());
        $error = true;
    }
}

if (!$allDone) {
    if (!$error) {
        if ($op === "create") {
            if (Customer::insertCustomer($excludeCreditCardNumber)) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['saveCustomerSuccess']}</div>";
            }
        } elseif ($op === 'edit') {
            if (Customer::updateCustomer($_GET['id'], $excludeCreditCardNumber)) {
                $displayBlock = "<div class='si_message_ok'>{$LANG['saveCustomerSuccess']}</div>";
            }
        }
    }

    $smarty->assign('pageActive', 'customer');
    $smarty->assign('activeTab', '#people');
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);
