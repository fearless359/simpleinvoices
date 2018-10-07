<?php
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
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$biller_count   = Biller::count();
$customer_count = Customer::count();
$product_count  = Product::count();
if ($biller_count == 0 || $customer_count == 0 || $product_count == 0) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$path1 = './extensions/sub_customers/include';
$curr_path = get_include_path();
if (!strstr($curr_path, $path1)) {
    $path2 = $path1 . '/class';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path1 . PATH_SEPARATOR . $path2);
}


// Create & initialize DB table if it doesn't exist.
SubCustomers::addParentCustomerId();

$smarty->assign('number_of_customers', $count);

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
