<?php
global $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
$today = date("YmdGisa");
$oBack = new BackupDb();
$dirname = './tmp/database_backups';
$filename = $dirname. "/simple_invoices_backup_$today.sql"; // output file name
$oBack->skipit('si_log');
$on = $_REQUEST['checkbox'];
$tables = $pdoDb->query("SHOW TABLES");
$array = array();
$array2 = array();
foreach ($tables as $t)
{
	if (!array_key_exists($t[0], $on))
		$array[] = $t[0];
//	else
//		$array2[] = $t[0];
}
foreach ($array as $a)
{
	$oBack->skipit($a);
error_log('new_backup.php: skipping '. $a);
}
$oBack->start_backup($filename);
$txt = sprintf($LANG['backup_done'],$filename);

$smarty->assign('filename', $filename);
$smarty->assign('txt', $txt);
$smarty->assign('output', $oBack->getOutput());
$smarty->assign("defaults", getSystemDefaults());
$smarty->assign('pageActive', 'backup');
$smarty->assign('active_tab', '#setting');

//$smarty->assign('on', $on);
//$smarty->assign('tables', $tables);
$smarty->assign('array', $array);
//$smarty->assign('array2', $array2);

