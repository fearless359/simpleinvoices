<?php

use Inc\Claz\Biller;
use Inc\Claz\CustomFields;
use Inc\Claz\Log;
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
Log::out("session: " . print_r($_SESSION, true));
if ($_SESSION['role_name'] == 'biller' && $_SESSION['user_id'] != $_GET['id']) {
//    header('Location: index.php?module=errorPages&view=401');
    header("HTTP/1.0 401 Not authorized");
    exit();
}

Util::directAccessAllowed();

$smarty->assign('biller', Biller::getOne($_GET['id']));
$smarty->assign('files', Util::getLogoList());
$smarty->assign('customFieldLabel', CustomFields::getLabels(true));

$smarty->assign('pageActive', 'biller');
$smarty->assign('subPageActive', 'billerEdit');
$smarty->assign('activeTab', '#people');
