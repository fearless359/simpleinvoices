<?php
/*
* Script: ./extensions/matts_luxury_pack/include/init.php
* 	initialization
*
* Authors:
*	 git0matt@gmail.com
*
* Last edited:
* 	 2016-09-15
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//global $LANG;
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$start_time = microtime_float();
//echo '<script>alert("matts_luxury_pack-init:req ['.$_SERVER['REQUEST_TIME_FLOAT'].']['.$start_time.']")</script>';
$array0to9 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
$pagerows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);
$smarty->assign("version_name", $config->version->name);

//include_once './extensions/<THIS NAME>/include/sql_queries.php';	// not active yet...

/*
function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
}
set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
*/
function DBcolumnExists($table, $column) {
	return checkFieldExists($table, $column);
}
/*if (!function_exists ('addDatabaseColumn'))
{		^*TAKES TOO LONG*/
	function addDatabaseColumn ($column, $table, $type, $length, $cannull=false, $def_value="", $after="")
	{
		global $LANG, $dbh;

		$sql = "SELECT data_type FROM information_schema.columns WHERE table_name='$table' AND column_name='$column';";
	error_log ("exists($table.$column)...$sql");
		if (($sth = $dbh->query ($sql)) === false)
		{
			// Non-critical error so continue with next action.
			error_log ("Error: ".print_r($sth->errorInfo(),true)." in matts_luxury_pack - addDatabaseColumn: $sql");
		} else
		{
			$row = $sth->fetch (PDO::FETCH_ASSOC);
			if (strtolower($row['data_type']) != strtolower($type))
			{
				$length = strstr($length, '.', ',');//str_replace('.', ',', $length);
				$sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type( $length )";
				$sql.= $cannull ? " NOT NULL" : " NULL";
				$sql.= isset($def_value) ? " DEFAULT '$def_value'" : "";
				$sql.= $after ? " AFTER `$after`" : "";
				$sql.= ";";
	error_log ("add($table.$column)...$sql|");
				if (($sth = $dbh->query ($sql)) === false)
				{
					// Non-critical error so continue with next action.
					if ($sth)      $error = print_r($sth->errorInfo(), true);
//					error_log ("Error: $error in matts_luxury_pack - addDatabaseColumn: $sql");
				}
			}
		}
		return true;
	}
/*}			*TAKES TOO LONG*/

include_once ('extensions/matts_luxury_pack/include/class/myexport.php');
//echo '<script>alert("matts_luxury_pack-init:77 ['.((microtime_float()-$start_time)*100).']")</script>';//0.2
/********************* sql_queries section ***********************/
/**/
if (!DBcolumnExists(TB_PREFIX."si_log", "function")) {
	$sql = "ALTER TABLE ".TB_PREFIX."si_log :act :name :type";
	$sqlcol = " COLLATE :collate :ull";
	$sqllen = " ( :length ):ull";
	dbQuery($sql.$sqlcol, ":act", "ADD", ":name", "function", ":type", "text", ":collate", "utf8_unicode_ci", ":ull", "NOT NULL");
	dbQuery($sql.$sqlcol, ":act", "ADD", ":name", "file", ":type", "text", ":collate", "utf8_unicode_ci", ":ull", "NOT NULL");
	dbQuery($sql.$sqllen, ":act", "ADD", ":name", "line", ":type", "INT", ":length", "11", ":ull", "NOT NULL");
	dbQuery($sql.$sqlcol, ":act", "ADD", ":name", "proposed", ":type", "text", ":collate", "utf8_unicode_ci", ":ull", "NOT NULL");
	dbQuery($sql.$sqlcol, ":act", "ADD", ":name", "stack", ":type", "text", ":collate", "utf8_unicode_ci", ":ull", "NOT NULL");
}
/**/
/********************* customer section ***********************/

//include_once ('extensions/matts_luxury_pack/include/class/mycustomer.php');
include_once ('extensions/matts_luxury_pack/include/customer.functs.php');
$sql = "ALTER TABLE ".TB_PREFIX."customers :act :name :type ( :length ):ull";

if (!DBcolumnExists(TB_PREFIX."customers", "credit_card_cvc")) {
	$sqldef = " DEFAULT :def";
	$sqlaft = " AFTER :after;";
	dbQuery($sql.$sqlaft, ":act", "ADD", ":name", "credit_card_cvc", ":type", "INT", ":length", "11", ":ull", "NOT NULL", ":after", "credit_card_number");
	dbQuery($sql.$sqldef, ":act", "MODIFY COLUMN", ":name", "credit_card_expiry_year", ":type", "INT", ":length", "11", ":ull", "NOT NULL", ":def", "NULL");
	dbQuery($sql.$sqldef, ":act", "MODIFY COLUMN", ":name", "credit_card_expiry_month", ":type", "INT", ":length", "11", ":ull", "NOT NULL", ":def", "NULL");
/*
	$dbh->beginTransaction();
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers ADD credit_card_cvc INT(11) NOT NULL AFTER credit_card_number");
	//My SQL / Oracle (prior version 10G):
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers MODIFY COLUMN credit_card_expiry_year INT(11) NULL DEFAULT NULL");
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers MODIFY COLUMN credit_card_expiry_month INT(11) NULL DEFAULT NULL");
	//SQL Server / MS Access:
	//ALTER TABLE table_name ALTER COLUMN column_name datatype
	//Oracle 10G and later:
	//ALTER TABLE table_name MODIFY column_name datatype
	$dbh->commit();
*/
}
//addDatabaseColumn ('price_list', TB_PREFIX.'customers', 'int', 11);
if (!DBcolumnExists(TB_PREFIX."customers", "price_list")) {
//	$sql = "ALTER TABLE ".TB_PREFIX."customers :act :name :type ( :length ):ull";
	dbQuery($sql, ":act", "ADD COLUMN", ":name", "price_list", ":type", "INT", ":length", "11", ":ull", "NOT NULL");
/*
	$dbh->beginTransaction();
	$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers ADD COLUMN price_list INT(11) NOT NULL");
	$dbh->commit();
*/
}
//echo '<script>alert("matts_luxury_pack-init:113 ['.((microtime_float()-$start_time)*100).']")</script>';//135
/******************************** product section **************************/
include_once ('extensions/matts_luxury_pack/include/class/myproduct.php');

//{if $defaults.multiple_prices}
addDatabaseColumn ('unit_list_price2', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
addDatabaseColumn ('unit_list_price3', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
addDatabaseColumn ('unit_list_price4', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
//echo '<script>alert("matts_luxury_pack-init:120 ['.((microtime_float()-$start_time)*100).']")</script>';//335
/******************************** invoice section *******************************/
include_once ('extensions/matts_luxury_pack/include/class/myinvoice.php');

//{if $defaults.use_ship_to}
addDatabaseColumn ('ship_to_customer_id', TB_PREFIX.'invoices', 'int', 11, false, 0, 'customer_id');
//{if $defaults.use_terms}
addDatabaseColumn ("terms", TB_PREFIX."invoices", "varchar", 100);
//echo '<script>alert("matts_luxury_pack-init:126 ['.((microtime_float()-$start_time)*100).']")</script>';//500
/****************************** payments section *********************************/
//include ('extensions/matts_luxury_pack/include/class/mypayments.php');
include_once ('extensions/matts_luxury_pack/include/payments.functs.php');
//echo '<script>alert("matts_luxury_pack-init:130 ['.((microtime_float()-$start_time)*100).']")</script>';//501
