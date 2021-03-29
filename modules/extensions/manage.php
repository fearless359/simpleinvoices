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
$plugins[] = "<img src=\"images/plugin_disabled.png\" alt=\"{$LANG['pluginNotRegistered']}\" title=\"{$LANG['pluginNotRegistered']}\" />";
$plugins[] = "<img src=\"images/plugin.png\"          alt=\"{$LANG['pluginRegistered']}\" title=\"{$LANG['pluginRegistered']}\" />";
$plugins[] = "<img src=\"images/plugin_delete.png\"   alt=\"{$LANG['pluginUnregister']}\" title=\"{$LANG['pluginUnregister']}\" />";
$plugins[] = "<img src=\"images/plugin_add.png\"      alt=\"{$LANG['pluginRegister']}\" title=\"{$LANG['pluginRegister']}\" />";$smarty->assign('plugins', $plugins);

$lights = [];
$lights[] = "<img src=\"images/lightbulb_off.png\"    alt=\"{$LANG['disabled']}\" title=\"{$LANG['disabled']}\" />";
$lights[] = "<img src=\"images/lightbulb.png\"        alt=\"{$LANG['enabled']}\" title=\"{$LANG['enabled']}\" />";
$lights[] = "<img src=\"images/lightswitch16x16.png\" alt=\"{$LANG['toggleStatus']}\" title=\"{$LANG['toggleStatus']}\" />";$smarty->assign('lights', $lights);

$rows = Extensions::getAllWithDirs();

$extensions = [];
foreach ($rows as $row) {
    $row['image'] = $plugins[3 - $row['registered']];
    $extensions[] = $row;
}

$smarty->assign("extensions", $extensions);
$smarty->assign('numberOfRows', count($extensions));

$smarty->assign('pageActive', 'setting');
$smarty->assign('activeTab', '#setting');
$smarty->assign('subPageActive', 'setting_extensions');
