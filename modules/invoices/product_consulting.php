<?php

use Inc\Claz\DynamicJs;
use Inc\Claz\Util;

/*
 *  Script: consulting.php
 * 	    consulting invoice page
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 * 	    2007-07-19
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 * 	    https://simpleinvoices.group
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

DynamicJs::begin();
DynamicJs::formValidationBegin("frmpost");
DynamicJs::testValidation("biller_id","Biller Name",1,1000000);
DynamicJs::testValidation("customer_id","Customer Name",1,1000000);
DynamicJs::validateIfNumZero("i_quantity0","Quantity");
DynamicJs::validateIfNum("i_quantity0","Quantity");
DynamicJs::validateRequired("select_products0","Product");
DynamicJs::testValidation("select_tax","Tax Rate",1,100);
DynamicJs::preferenceValidation("select_preferences","Invoice Preference",1,1000000);
DynamicJs::formValidationEnd();
DynamicJs::end();

$pageActive = "invoices";

include('./modules/invoices/invoice.php');
