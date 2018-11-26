<?php

use Inc\Claz\Extensions;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
    if (!Extensions::setStatusExtension($extension_id)) {
        die(Util::htmlsafe("Something went wrong with the status change!"));
    }
}

$smarty -> assign("exts", Extensions::getAll());

$smarty -> assign('pageActive', 'setting');
$smarty -> assign('active_tab', '#setting');
$smarty -> assign('subPageActive', 'setting_extensions');
