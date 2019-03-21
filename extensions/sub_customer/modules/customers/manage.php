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
 *      2018-12-10 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Create & initialize DB table if it doesn't exist.
SubCustomers::addParentCustomerId();

/**********************************************************************
 * These settings are used to force user to enter the first biller,
 * customer and product when a new installation with empty database
 * has been performed.
 * TODO: Need better way to determine if still in initialization phase.
 */
$customers = Customer::manageTableInfo();
$customer_count = count($customers);
$biller_count = Biller::count();
$product_count  = Product::count();

$smarty->assign('number_of_billers'  , $biller_count);
$smarty->assign('number_of_customers', $customer_count);
$smarty->assign('number_of_products' , $product_count);

$first_run_wizard = false;
if ($biller_count == 0 || $customer_count == 0 || $product_count == 0) {
    $first_run_wizard =true;
}
$smarty->assign("first_run_wizard",$first_run_wizard);
/**********************************************************************/

// No need to do all this if first_run_wizard specified
if (!$first_run_wizard) {
    $data = json_encode(array('data' => $customers));
    if (file_put_contents("public/data.json", $data) === false) {
        die("Unable to create public/data.json file");
    }
    $smarty->assign('number_of_rows', $customer_count);
}

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
