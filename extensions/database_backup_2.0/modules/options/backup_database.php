<?php
global $smarty, $pagerows, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$entries = Dir::get(SI_ABSROOT. '/tmp/database_backups/', 'sql');
if (!is_array($entries))
	error_log('no sql files in '. SI_ABSROOT. '/tmp/database_backups/');
$tables = $pdoDb->query("SHOW TABLES");//myBackupDb::show_tables();
$array = array();
foreach($tables as $t)
{
	$array[] = array('name' => $t[0], 'action' => 'do');
}
//if (!$array)		$array = array(array('name'=>'qwerty','action'=>'lkjh'), array('name'=>'asdf', 'action'=>'yes'));
$smarty->assign("defaults", getSystemDefaults());
$smarty->assign("tables", $array);
$smarty->assign("entries", $entries);
$smarty->assign("number_of_rows", count($entries));
$smarty->assign('pageActive', 'backup');
$smarty->assign('active_tab', '#setting');
$smarty->assign("array", $pagerows);

//$smarty->assign("mod_dir", substr(si_web_path (dirname(dirname(__FILE__))), 2));

