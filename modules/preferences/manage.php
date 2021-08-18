<?php

use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$preferences = Preferences::manageTableInfo();

$data = json_encode(['data' => $preferences]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($preferences));

$smarty->assign('pageActive', 'invPrefs');
$smarty->assign('activeTab', '#settings');
