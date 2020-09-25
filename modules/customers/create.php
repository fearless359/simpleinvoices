<?php

use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\DomainId;
use Inc\Claz\Util;

/*
 * Script: create.php
 * 	    Customers create page
 *
 * Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	 2018-12-21 by Richard Rowley
 *
 * License:
 *	    GPL v3 or above
 *
 * Website:
 * 	    https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['op']) && $_POST['op'] == 'create' && !empty($_POST['name'])) {
    include "modules/customers/save.php";
} else {
    $customFieldLabel = CustomFields::getLabels(true);
    $smarty->assign('customFieldLabel', $customFieldLabel);
    $smarty->assign('domain_id', DomainId::get());
    
    $smarty->assign('parent_customers', Customer::getAll(['enabled_only' => true]));
    
    $smarty->assign('pageActive', 'customer');
    $smarty->assign('subPageActive', 'customer_add');
    $smarty->assign('active_tab', '#people');
}
