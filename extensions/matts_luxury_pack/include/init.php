<?php
/*
 * Script: ./extensions/matts_luxury_pack/init.php
 * 	Initialization
 *
 * Authors:
 *	 git0matt@gmail.com
 *
 * Last edited:
 * 	 2016-09-16
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
if (!isset($matthere))
{
	define('SI_ABSROOT', dirname(dirname(dirname(dirname(__FILE__)))));
	$smarty->assign('SI_ABSROOT', SI_ABSROOT);
/*	$out = list_css();
	$smarty->assign('css_files', $out);
	$out = list_js();
	$smarty->assign('js_files', $out);*/
	$matthere = realpath(dirname(__FILE__));
	$smarty->assign('mlpabs', $matthere);
	$mlprel = str_replace(dirname($_SERVER['PHP_SELF']), ".", dirname(strstr($matthere, dirname($_SERVER['PHP_SELF'])))). '/';
	$smarty->assign('mlprel', $mlprel);

	//echo '<script>alert("matts_luxury_pack-init:req ['.$_SERVER['REQUEST_TIME_FLOAT'].']['.$start_time.']")</script>';

	//include_once "$matthere/sql_queries.php";	//not needed here
	//set_include_path(get_include_path() . PATH_SEPARATOR . "./$matthere/");	// files are not loaded on-demand
	set_include_path(get_include_path() . PATH_SEPARATOR . "$matthere/class/");	// load classes on-demand
	$mytime = new mytime;	// begin execution timer
	error_log('loading //matts_luxury_pack//init.php at '. $mytime->took());//date("Y-m-d H:i:s", 

	modifyDB::log();
	$array0to9 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);	// often used array
	$pagerows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);	// rows-per-page array
	$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$mons = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$smarty->assign("version_name", $config->version->name);	// put version_name into page header template
	include_once ($matthere. '/customer.functs.php');
	//$logger->log('include_once ('. $matthere. '/customer.functs.php)', Zend_Log::INFO);
	error_log('matts_luxury_pack-init:include_once ('. $matthere. '/customer.functs.php) in '. $mytime->took());
	include_once ($matthere. '/payments.functs.php');
	//$logger->log('include_once ('. $matthere. '/payments.functs.php)', Zend_Log::INFO);
	error_log('matts_luxury_pack-init:include_once ('. $matthere. '/payments.functs.php) in '. $mytime->took());

	/*
	function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
	}
	set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
	*/
	function DBcolumnExists($table, $column) {	// use another name for ...
		return checkFieldExists($table, $column);
	}
	
	function parseURLencoded($url)
	{
		$pos = strpos($url, '%3F');						// find '?'
		if ($pos === false)
			return false;								// return if not found
		$string = substr($url, ((int)$pos+3));
		$pairs = explode ('%26', $string);				// split pairs on '&'
		$array = array();
	//error_log('index.php:string:'. $string);
		foreach ($pairs as $p)
		{
			$parts = explode ('%3D', $p, 2);			// split on '='
			$array[$parts[0]] = $parts[1];				// insert string before => string after into assoc. array
		}
	//error_log('index.php:array:'. print_r($array,true));
		return $array;
	}

}
