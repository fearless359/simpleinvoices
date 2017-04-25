<?php
/*
 * Script: ./extensions/database_backup_2.0/include/init.php
 * 	Initialization
 *
 * Authors:
 *	 git0matt@gmail.com
 *
 * Last edited:
 * 	 2017-04-06
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
if (!isset($bdbabs))
{
	$bdbabs = realpath(dirname(__FILE__));
	$bdbrel = str_replace(dirname($_SERVER['PHP_SELF']), ".", dirname(strstr($bdbabs, dirname($_SERVER['PHP_SELF'])))). '/';
	error_log('loading '. $bdbrel. 'include/init.php |memory:'. memory_get_usage());
	defined('SI_ABSROOT') || define('SI_ABSROOT', dirname(dirname(dirname(dirname(__FILE__)))));

	set_include_path(get_include_path() . PATH_SEPARATOR . "$bdbabs/class");	// load classes on-demand

//	error_log('including '. $bdbabs. '/class/.');
/*	if (!function_exists('DBcolumnExists'))
	{
		function DBcolumnExists($table, $column) {	// use another name for ...
			return checkFieldExists($table, $column);
		}
	}*/
/*	if (!function_exists('parseURLencoded'))
	{
		function parseURLencoded($url)
		{
			$pos = strpos($url, '%3F');					// find '?'
			if ($pos === false)
				return false;						// return if not found
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
	}*/
}

