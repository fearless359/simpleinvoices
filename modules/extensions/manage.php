<?php

use Inc\Claz\Extensions;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
    if (!setStatusExtension($extension_id)) {
        die(htmlsafe("Something went wrong with the status change!"));
    }
}

$smarty -> assign("exts", Extensions::getAll());

$smarty -> assign('pageActive', 'setting');
$smarty -> assign('active_tab', '#setting');
$smarty -> assign('subPageActive', 'setting_extensions');
