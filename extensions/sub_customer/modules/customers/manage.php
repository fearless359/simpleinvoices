<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Product;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Customers manage page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

// Create & initialize DB table if it doesn't exist.
SubCustomers::addParentCustomerId();

$biller_count   = Biller::count();
$customers      = Customer::getAll();
$customer_count = count($customers);
$product_count  = Product::count();


$first_run_wizard = false;
if ($biller_count == 0 || $customer_count == 0 || $product_count == 0) {
    $first_run_wizard =true;
}
$smarty->assign("first_run_wizard",$first_run_wizard);

$smarty->assign('number_of_billers', $biller_count);
$smarty->assign('number_of_customers', $customer_count);
$smarty->assign('number_of_products', $product_count);

$smarty->assign('customers', $customers);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
