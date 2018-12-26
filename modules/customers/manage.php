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
Util::isAccessAllowed();

/**********************************************************************
 * These settings are used to force user to enter the first biller,
 * customer and product when a new installation with empty database
 * has been performed.
 * TODO: Need better way to determine if still in initialization phase.
 */
$rows = Customer::getAll();
$customer_count = count($rows);
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
    $viewcust = $LANG['view'] . " " . $LANG['customer'];
    $editcust = $LANG['edit'] . " " . $LANG['customer'];
    $defaultinv = $LANG['new_uppercase'] . " " . $LANG['default_invoice'];
    $customers = array();
    foreach ($rows as $row) {
        $row['vname'] = $viewcust . $row['name'];
        $row['ename'] = $editcust . $row['name'];
        $row['image'] = ($row['enabled'] == ENABLED ? "images/common/tick.png" : "images/common/cross.png");
        $customers[] = $row;
    }

    $smarty->assign('customers', $customers);
    $smarty->assign('number_of_rows', $customer_count);

    $smarty->assign('viewcust', $viewcust);
    $smarty->assign('editcust', $editcust);
    $smarty->assign("defaultinv", $defaultinv);
}

$smarty->assign('pageActive', 'customer');
$smarty->assign('active_tab', '#people');
