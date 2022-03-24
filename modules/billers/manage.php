<?php

use Inc\Claz\Biller;
use Inc\Claz\Util;

/*
 * Script: manage.php
 * Biller manage page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2016-01-16 by Rich Rowley to add signature field
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group */
global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::directAccessAllowed();

$billers = Biller::manageTableInfo();
$data = json_encode(['data' => mb_convert_encoding($billers, 'UTF-8')]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($billers));

$smarty->assign('pageActive', 'biller');
$smarty->assign('activeTab', '#people');
