<?php

use Inc\Claz\Util;

/*
 *  Script: consulting.php
 *      consulting invoice page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-12-24 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

include './modules/invoices/invoice.php';

$smarty->assign('pageActive', 'invoice_new');
$smarty->assign('activeTab', '#money');
