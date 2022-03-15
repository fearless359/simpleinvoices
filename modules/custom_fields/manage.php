<?php

use Inc\Claz\CustomFields;
use Inc\Claz\Util;

/*
 * Script: manage.php
 *     Custom fields manage page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cfs = CustomFields::manageTableInfo();

$data = json_encode(['data' => mb_convert_encoding($cfs, 'UTF-8')]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($cfs));

$smarty->assign('pageActive', 'customFields');
$smarty->assign('activeTab', '#settings');
