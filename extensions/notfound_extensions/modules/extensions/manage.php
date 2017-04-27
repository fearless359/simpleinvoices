<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
$messages = array();
function getExtensions() {
	global $LANG;
	$domain_id = class_exists('domain_id') ? domain_id::get() : si_classes\DomainId::get();
	
	$sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE domain_id = 0 OR domain_id = :domain_id ORDER BY name";

	$sth = dbQuery($sql, ':domain_id', $domain_id);
	
	$exts = null;
	
	for($i=0; $ext = $sth->fetch(); $i++) {
		$exts[$i] = $ext;
	}
	
	return $exts;
}

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
	setStatusExtension($extension_id) or die(htmlsafe("Something went wrong with the status change!"));
}
$exts = getExtensions();
$smarty->assign("exts", $exts);
foreach($exts as $ext)
{
	if ($ext['name']=='core' || !$ext['enabled'])		continue;
	$file = 'extensions/'. $ext['name'];
	if (!file_exists($file))
		$messages[] = $LANG['dir_notfound']. ': '. $LANG['extension']. ' "'. $ext['name']. '", '.
			' <a href="./index.php?module=extensions&amp;view=manage&amp;id='. $ext['id']. '&amp;action=toggle">'. $LANG['disable']. '</a>';
}
$smarty->assign('pageActive', 'setting');
$smarty->assign('active_tab', '#setting');
$smarty->assign('subPageActive', 'setting_extensions');
$smarty->assign('messages', $messages);

