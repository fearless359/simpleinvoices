<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\DomainId;
use Inc\Claz\Util;

/*
 * Script: add.php
 * 	Customers add page
 *
 * Authors:
 *	 Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	 2018-12-21 by Richard Rowley
 *
 * License:
 *	 GPL v3 or above
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['op']) && $_POST['op'] == 'add' && !empty($_POST['name'])) {
    include("extensions/sub_customer/modules/customers/save.php");
} else {
    $customFieldLabel = CustomFields::getLabels(true);
    $smarty->assign('customFieldLabel', $customFieldLabel);
    $smarty->assign('domain_id', DomainId::get());
    
    $parent_customers = Customer::getAll(true);
    $smarty->assign('parent_customers', $parent_customers);
    
    $smarty->assign('pageActive', 'customer');
    $smarty->assign('subPageActive', 'customer_add');
    $smarty->assign('active_tab', '#people');
}