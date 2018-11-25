<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Product;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Customers manage page
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$biller_count   = Biller::count();
$customer_count = Customer::count();
$product_count  = Product::count();

if ($biller_count == 0 || $customer_count == 0 || $product_count == 0) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$smarty->assign('number_of_customers', $customer_count);
$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
