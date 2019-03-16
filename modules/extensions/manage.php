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
$plugins[] = "<img src=\"images/famfam/plugin_disabled.png\" alt=\"{$LANG['plugin_not_registered']}\" title=\"{$LANG['plugin_not_registered']}\" />";
$plugins[] = "<img src=\"images/famfam/plugin.png\"          alt=\"{$LANG['plugin_registered']}\" title=\"{$LANG['plugin_registered']}\" />";
$plugins[] = "<img src=\"images/famfam/plugin_delete.png\"   alt=\"{$LANG['plugin_unregister']}\" title=\"{$LANG['plugin_unregister']}\" />";
$plugins[] = "<img src=\"images/famfam/plugin_add.png\"      alt=\"{$LANG['plugin_register']}\" title=\"{$LANG['plugin_register']}\" />";
$smarty->assign('plugins', $plugins);

$lights = array();
$lights[] = "<img src=\"images/famfam/lightbulb_off.png\"    alt=\"{$LANG['disabled']}\" title=\"{$LANG['disabled']}\" />";
$lights[] = "<img src=\"images/famfam/lightbulb.png\"        alt=\"{$LANG['enabled']}\" title=\"{$LANG['enabled']}\" />";
$lights[] = "<img src=\"images/common/lightswitch16x16.png\" alt=\"{$LANG['toggle_status']}\" title=\"{$LANG['toggle_status']}\" />";
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
