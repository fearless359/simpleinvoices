<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 * Script: details.php
 * 	Customers details page
 *
 * Authors:
 *	 Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *      2018-12-21 by Richard Rowley
 *
 * License:
 *	    GPL v3 or above
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cid = $_GET['id'];

$customer = Customer::getOne($cid);
$customer['credit_card_number_masked'] = Customer::maskCreditCardNumber($customer['credit_card_number']);
$smarty->assign('customer', $customer);

// Get the customers that have this customer as their parent
$subCustomerEnabled = SystemDefaults::getSubCustomer();
$smarty->assign('subCustomerEnabled', $subCustomerEnabled);
if ($subCustomerEnabled) {
    $children = Customer::getMyChildren($cid);
    if (empty($children)) {
        // We aren't a parent so see if we are child
        $myParent = Customer::getMyParent($customer['parent_customer_id']);
        $smarty->assign('parentCustomer', $myParent);
    } else {
        // We are a child.
        $smarty->assign("childCustomers", $children);
    }
}
$smarty->assign('invoices',Customer::getCustomerInvoices($cid));

$smarty->assign('customFieldLabel', CustomFields::getLabels(true));

try {
    $invoicesOwing = Invoice::getInvoicesOwing($cid);
    $smarty->assign('invoices_owing', $invoicesOwing);
    $smarty->assign('invoices_owing_count', count($invoicesOwing));
} catch (PdoDbException $pde) {
    exit("modules/customers/details.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('pageActive', 'customer');
$smarty->assign('subPageActive', "customerView");
$smarty->assign('activeTab', '#people');
