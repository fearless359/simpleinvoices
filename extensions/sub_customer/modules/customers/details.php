<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\Util;

/*
 *  Script: details.php
 *      Customers details page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-12-21 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cid = $_GET['id'];

$customer = Customer::getOne($cid);
$customer['credit_card_number_masked'] = Customer::maskCreditCardNumber($customer['credit_card_number']);
$smarty->assign('customer',$customer);

// Get the customers that have this customer as their parent
$subCustomers = SubCustomers::getSubCustomers($cid);
$smarty->assign('subCustomers',$subCustomers);
$smarty->assign('subCustomerCount', count($subCustomers));

$parentCustomers = Customer::getAll(['enabled_only' => true]);
$smarty->assign('parent_customers', $parentCustomers);

$invoices = Customer::getCustomerInvoices($cid);
$smarty->assign('invoices',$invoices);

$customFieldLabel = CustomFields::getLabels(true);
$smarty->assign('customFieldLabel',$customFieldLabel);

$invoicesOwing = Invoice::getInvoicesOwing($cid);
$smarty->assign('invoices_owing'  , $invoicesOwing);
$smarty->assign('invoices_owing_count', count($invoicesOwing));

$smarty->assign('pageActive', 'customer');
$subPageActive  = $_GET['action'] == "view"  ? "customer_view" : "customer_edit";
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#people');
