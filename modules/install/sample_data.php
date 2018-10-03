<?php
global $db, $smarty;

$menu = false;

$samplejson = new ImportJson();
$samplejson->file = "databases/json/sample_data.json";
$samplejson->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
$samplejson->pattern_replace = array(TB_PREFIX,'1','en_US','en_US');
$saved = ($db->query($samplejson->collate()));

$smarty -> assign("saved",$saved);
