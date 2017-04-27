<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/invoices/xml.php
 * 	invoice grid XML
 *
 * Authors:
 *	git0matt@gmail.com
 *
 * Last edited:
 * 	2016-09-24
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */

/*
API calls don't use the auth module 
*/
if ($module != 'api'){
	if (!isset($auth_session->id)){
		if (!isset($_GET['module'])) {
			$_GET['module'] = '';
		}
		if ($_GET['module'] !== "auth") {
			if (isset($_SERVER['HTTP_REFERER']))
			{
				$pos = strpos ($_SERVER['HTTP_REFERER'], '?');
				$from = urlencode (substr ($_SERVER['HTTP_REFERER'], $pos));
			} else
				$from = '';
//		error_log('login from '. $from);
			header('Location: index.php?module=auth&view=login&from='. $from);
			exit;
		}
	}
}
function parseURL($url)
{
	$url_parts = parse_url ($url);
	if (isset($url_parts['query']))
	{
		parse_str ($url_parts['query'], $query_parts);
//			error_log('parseURL:'. print_r($query_parts,true));
		return $query_parts;
	}
}
