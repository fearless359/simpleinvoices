<?php

use Inc\Claz\Extensions;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
    if (!Extensions::setStatusExtension($extension_id)) {
        die(Util::htmlsafe("Something went wrong with the status change!"));
    }
}

$plugins = array();
$plugins[] = " />";
$plugins[] = " />";
$plugins[] = " />";
$plugins[] = " />";
$smarty->assign('plugins', $plugins);

$lights = array();
$lights[] = " />";
$lights[] = " />";
$lights[] = " />";
$smarty->assign('lights', $lights);

$rows = Extensions::getAllWithDirs();
$extensions = array();
foreach ($rows as $row) {
    $row['image'] = $plugins[3 - $row['registered']];
    $extensions[] = $row;
}
$smarty->assign("extensions", $extensions);
$smarty->assign('number_of_rows', count($extensions));

$smarty->assign('pageActive', 'setting');
$smarty->assign('active_tab', '#setting');
$smarty->assign('subPageActive', 'setting_extensions');
