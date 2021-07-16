<?php
use Inc\Claz\Util;

/*
 * Script: itemized.php
 *   Create an itemized invoice page
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2021-06-17 by Rich Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

include './modules/invoices/invoice.php';

$smarty->assign('pageActive', 'invoice');
$smarty->assign('subPageActive', 'invoiceCreate');
$smarty->assign('activeTab', '#money');
