<?php

use Inc\Claz\Biller;
use Inc\Claz\CustomFields;
use Inc\Claz\Util;

/*
 * Script: details.php
 *   Biller details page
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2007-07-19 
 *
 * License:
 *   GPL v2 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$smarty->assign('biller', Biller::getOne($_GET['id']));
$smarty->assign('files', Util::getLogoList());
$smarty->assign('customFieldLabel', CustomFields::getLabels(true));

$smarty->assign('pageActive', 'biller');
$subPageActive = $_GET['action'] =="view"  ? "biller_view" : "biller_edit" ;
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab', '#people');
