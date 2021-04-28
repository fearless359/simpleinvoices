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
        die(Util::htmlSafe("Something went wrong with the status change!"));
    }
}

$extensions = Extensions::manageTableInfo();

$data = json_encode(['data' => $extensions]);
if (file_put_contents("public/data.json", $data) === false) {
    die("Unable to create public/data.json file");
}

$smarty->assign('numberOfRows', count($extensions));

$smarty->assign('pageActive', 'setting');
$smarty->assign('activeTab', '#setting');
$smarty->assign('subPageActive', 'setting_extensions');
