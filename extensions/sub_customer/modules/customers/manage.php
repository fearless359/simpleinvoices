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
$customerCount = count($customers);
$billerCount = Biller::count();
$productCount  = Product::count();

$smarty->assign('number_of_billers'  , $billerCount);
$smarty->assign('number_of_customers', $customerCount);
$smarty->assign('number_of_products' , $productCount);

$firstRunWizard = false;
if ($billerCount == 0 || $customerCount == 0 || $productCount == 0) {
    $firstRunWizard =true;
}
$smarty->assign("first_run_wizard",$firstRunWizard);
/**********************************************************************/

// No need to do all this if first_run_wizard specified
if (!$firstRunWizard) {
    $data = json_encode(['data' => $customers]);
    if (file_put_contents("public/data.json", $data) === false) {
        die("Unable to create public/data.json file");
    }
    $smarty->assign('number_of_rows', $customerCount);
}

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
