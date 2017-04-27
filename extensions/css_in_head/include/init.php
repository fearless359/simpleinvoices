<?php
/*
 * Script: extensions/css_in_head/include/init.php
 *	Initialization
 *
 * Authors:
 *	git0matt@gmail.com
 *
 * Last edited:
 *	2017-04-26
 *
 * Designed for:
 *	2017.2
 */
global $ext_names, $smarty;
if (!isset($cihabs))
{
	$cihabs = realpath(dirname(__FILE__));
	//$cihrel = str_replace(dirname($_SERVER['PHP_SELF']), ".", dirname(strstr($cihabs, dirname($_SERVER['PHP_SELF'])))). '/';
$cihrel = substr(str_replace(dirname($_SERVER['PHP_SELF']), "", dirname(strstr($cihabs, dirname($_SERVER['PHP_SELF'])))). '/', 1);
	error_log('loading '. $cihrel. 'init.php |memory:'. memory_get_usage());
	defined('SI_ABSROOT') || define('SI_ABSROOT', dirname(dirname(dirname(dirname(__FILE__)))));
	$smarty->assign('header_tpl', $cihrel. 'templates/default/header.tpl');
	//$smarty->assign('header_tpl', 'templates/default/header.tpl');

	set_include_path(get_include_path() . PATH_SEPARATOR . "$cihabs/class");	// load classes on-demand
}

$styles = array();
$estyles = array();
//error_log('root is |'. SI_ABSROOT);//dirname(dirname(dirname(__FILE__))). '|');
foreach($ext_names as $ext)	// each enabled extension
{
	$file = "extensions/$ext/templates/default/css/css-map.tpl";
//error_log('css_in_head looking for...'. $file);
	if (file_exists($file))
	{
		//$smarty->assign('csspath', dirname($file));
		$estyles = file_get_contents($file);
		$estyles = str_replace('{$csspath}', dirname($file). '/', $estyles);
//error_log("YES: $estyles");
	} else {
		$file = "extensions/$ext/templates/default/css";
		if (file_exists($file))
		{
			$cssfiles = Dir::get($file, 'css');
			foreach($cssfiles as $cf)
			{
//error_log("css found...". $cf['path']. '/'. $cf['name']);
//			$styles[] = "\n/** ". $cf['name']. " **/\n". file_get_contents($file. '/'. $cf['name']);
				$estyles[] = '<link rel="stylesheet" type="text/css" href="'. $file. '/'. $cf['name']. '" />';
//error_log("styles:". print_r($styles, true));
			}
		}
	}
	$file = "extensions/$ext/templates/default/$module/$view.css";
//error_log('css_in_head looking for...'. $file);
	if (file_exists($file))
	{
error_log("css found...$file");
		$styles[] = "\n/** $file **/\n". file_get_contents($file);
//error_log("styles:". print_r($styles, true));
	}
}
//error_log("styles:". print_r($styles, true));
if (count($styles))
	$smarty->assign('hook_head_instyle', '<!--inline styles - loaded via css_in_head extension--><style type="text/css">'. "\n". implode("\n", $styles). "</style>\n");
if (is_array($estyles))
	$smarty->assign('hook_head_link', '<!--styles - loaded via css_in_head extension-->'. "\n". implode("\n", $estyles). "\n");
else if ($estyles)
	$smarty->assign('hook_head_link', "<!--styles - loaded via css_in_head extension-->\n$estyles\n");
