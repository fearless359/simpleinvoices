<?php
/*
* Script: extensions/better_database_backup/lang/en_CA/lang.php
* 	language translations
*
* Authors:
*	 git0matt@gmail.com
*
* Last edited:
* 	 2016-09-14
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
// @formatter:off
$MYC_LANG = array(
	'hide_options'				=> 'hide options',
	'backup_options'			=> 'show backup options',
	'size'					=> 'size',
	'filename'				=> 'filename',
	'action'				=> 'action',
	'tablename'				=> 'tablename',
	'success'				=> 'success',
	'skipped'				=> 'skipped',
	'status'				=> 'status',
	'skipped'				=> 'skipped',
	'no_backups'				=> 'no backups yet',
	'backup_tables' 			=> 'backup the checked database tables'
//	''								=> ''
);
// @formatter:on
global $defaults;
$LANG = array_merge($LANG, $MYC_LANG);
