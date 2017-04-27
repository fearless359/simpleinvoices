<?php
/*
 * Script: ./extensions/notfound_extensions/include/init.php
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
if (!isset($nfehere))
{
	$nfehere = realpath(dirname(__FILE__));
	$nfeweb = str_replace(dirname($_SERVER['PHP_SELF']), ".", dirname(strstr($nfehere, dirname($_SERVER['PHP_SELF'])))). '/';
	error_log('loading '. $nfeweb. 'include/init.php |memory:'. memory_get_usage());
	defined('SI_ABSROOT') || define('SI_ABSROOT', dirname(dirname(dirname(dirname(__FILE__)))));

	set_include_path(get_include_path() . PATH_SEPARATOR . "$nfehere/class");	// load classes on-demand
}
