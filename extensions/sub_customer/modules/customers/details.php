<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Extensions;
use Inc\Claz\Payment;
use Inc\Claz\Util;

/*
 *  Script: details.php
 *      Customers details page
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
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty, $LANG, $config;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// @formatter:off
$cid = $_GET['id'];

$customer = Customer::getOne($cid);
$customer['enabled_text'] = ($customer['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
if (empty($customer['credit_card_number'])) {
    $customer['credit_card_number_masked'] = "";
} else {
    try {
        $key = $config->encryption->default->key;
        $enc = new Encryption();
        $credit_card_number = $enc->decrypt($key, $customer['credit_card_number']);
        $customer['credit_card_number_masked'] = Customer::maskCreditCardNumber($credit_card_number);
    } catch (Exception $e) {
        throw new Exception("details.php - Unable to decrypt credit card for Customer, " .
                            $cid . ". " . $e->getMessage());
    }
}
$sub_customers = SubCustomers::getSubCustomers($cid);

$customer['total'] = Customer::calc_customer_total($customer['id']);
$customer['paid' ] = Payment::calcCustomerPaid($customer['id']);
$customer['owing'] = $customer['total'] - $customer['paid'];

$customFieldLabel = CustomFields::getLabels(true);
$invoices = Customer::getCustomerInvoices($cid);

$parent_customers = Customer::getAll(true);
$smarty->assign('parent_customers', $parent_customers);

$smarty->assign('customer',$customer);
$smarty->assign('sub_customers',$sub_customers);
$smarty->assign('invoices',$invoices);
$smarty->assign('customFieldLabel',$customFieldLabel);

$smarty->assign('pageActive', 'customer');

$subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive', 'customer');

$smarty->assign('active_tab', '#people');
