<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Custom flags manage page
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2018-12-13
 *
 *  License:
 *      GPL v3 or above
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cflgs = CustomFlags::manageTableInfo();

$data = json_encode(['data' => $cflgs]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($cflgs));

$smarty->assign('pageActive', 'customFlags');
$smarty->assign('activeTab', '#settings');
