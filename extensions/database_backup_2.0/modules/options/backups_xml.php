<?php
// extensions/better_database_backup/modules/options/backups_xml.php
header("Content-type: text/xml");
$xml = "";

$dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "ctime" ;
$rp = (isset($_POST['rp'])) ? $_POST['rp'] : '25' ;
$page = (isset($_POST['page'])) ? $_POST['page'] : '1' ;
if (intval($page) != $page)
	$start = 0;
else
$start = (($page-1) * $rp);
if (intval($rp) != $rp) {
	$rp = 25;
}
$valid_search_fields = array('name', 'ctime');
/*
	[file] => simple_invoices_backup_20170411185639pm.sql
	[path] => /hermes/bosnaweb21a/b1834/ipg.yumatechnicalcom/simple359/modules/options/../../tmp/database_backups/
	[0] => 50				[dev] => 50
	[1] => 333579650			[ino] => 333579650
	[2] => 33188				[mode] => 33188
	[3] => 1				[nlink] => 1
	[4] => 4996796				[uid] => 4996796
	[5] => 15010				[gid] => 15010
	[6] => 0				[rdev] => 0
	[7] => 415929				[size] => 415929
	[8] => 1491951399			[atime] => 1491951399
	[9] => 1491951403			[mtime] => 1491951403
	[10] => 1491951403			[ctime] => 1491951403
	[11] => 32768				[blksize] => 32768
	[12] => 824				[blocks] => 824
*/
	$realdir = SI_ABSROOT. '/tmp/database_backups/';
	$dirname = './tmp/database_backups';
	$entries = array();
	$sorted = array();
	$entries = Dir::get($realdir, 'sql');
	$total = count($entries);
	$sorted = is_array($entries) ? array_merge(array(), $entries) : array();
if ((isset($start) && $start) || (isset($rp) && $rp) && count($entries))
	$entries = array_slice($sorted, $start, $rp);
else
	$entries = is_array($sorted) ? array_merge(array(), $sorted) : array();
$xml = "<rows>
	<page>$page</page>
	<total>$total</total>
	<start-rp>$start-$rp</start-rp>
	<sort-dir>$sort-$dir</sort-dir>
";
$count = 0;
if (is_array($entries))
	foreach($entries as $out)
	{
		$xml .= "<row id='". $count++. "'>";
		$xml .= "<cell name='ctime'><![CDATA[
				". gmdate("Y-M-d [ h:i:s ]", $out['ctime']). "
			]]></cell>";
		$xml .= "<cell name='name'><![CDATA[
				<a href='$dirname/$out[name]'>$out[name]</a>
			]]></cell>";
		$xml .= "<cell name='size'><![CDATA[
				". round(($out['size'])/1024) ." K
			]]></cell>";
		$xml .= "</row>";
	}
else
	$xml .= "<norows>none</norows>";
$xml .= "</rows>";
echo $xml;
