<?php

use Inc\Claz\Extensions;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$extensionId = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action == 'toggle') {
    if (empty($extensionId) || !Extensions::setStatusExtension($extensionId)) {
        die(Util::htmlSafe("Something went wrong with the status change!"));
    }
}

$plugins = [];
$plugins[] = "<img src=\"images/plugin_disabled.png\" alt=\"{$LANG['plugin_not_registered']}\" title=\"{$LANG['plugin_not_registered']}\" />";
$plugins[] = "<img src=\"images/plugin.png\"          alt=\"{$LANG['plugin_registered']}\" title=\"{$LANG['plugin_registered']}\" />";
$plugins[] = "<img src=\"images/plugin_delete.png\"   alt=\"{$LANG['plugin_unregister']}\" title=\"{$LANG['plugin_unregister']}\" />";
$plugins[] = "<img src=\"images/plugin_add.png\"      alt=\"{$LANG['plugin_register']}\" title=\"{$LANG['plugin_register']}\" />";$smarty->assign('plugins', $plugins);

$lights = [];
$lights[] = "<img src=\"images/lightbulb_off.png\"    alt=\"{$LANG['disabled']}\" title=\"{$LANG['disabled']}\" />";
$lights[] = "<img src=\"images/lightbulb.png\"        alt=\"{$LANG['enabled']}\" title=\"{$LANG['enabled']}\" />";
$lights[] = "<img src=\"images/lightswitch16x16.png\" alt=\"{$LANG['toggle_status']}\" title=\"{$LANG['toggle_status']}\" />";$smarty->assign('lights', $lights);

$rows = Extensions::getAllWithDirs();

$extensions = [];
foreach ($rows as $row) {
    $row['image'] = $plugins[3 - $row['registered']];
    $extensions[] = $row;
}

$smarty->assign("extensions", $extensions);
$smarty->assign('number_of_rows', count($extensions));

$smarty->assign('pageActive', 'setting');
$smarty->assign('active_tab', '#setting');
$smarty->assign('subPageActive', 'setting_extensions');
