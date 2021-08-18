<?php

use Inc\Claz\Extensions;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$extensionId = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if ($action == 'toggle') {
    if (empty($extensionId) || !Extensions::setStatusExtension($extensionId)) {
        exit("Something went wrong with the status change!");
    }
}

$extensions = Extensions::manageTableInfo();

$data = json_encode(['data' => $extensions]);
if (file_put_contents("public/data.json", $data) === false) {
    exit("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($extensions));

$smarty->assign('pageActive', 'settings');
$smarty->assign('subPageActive', 'settingsExtensions');
$smarty->assign('activeTab', '#settings');
