<?php
/*
* Script: ./extensions/matts_luxury_pack/include/class/modifyDB.php
* 	modify the database tables
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

class modifyDB
{
	static public $debugit = true;
	
	
	public function __construct()
	{
		$this->debugit = true;
	}
	
	
	public static function addDatabaseColumn ($column, $table, $type, $length=null, $cannull=false, $def_value="", $after="", $collate=null)
	{
		global $LANG, $dbh, $mytime;

		$sql = "SELECT data_type FROM information_schema.columns WHERE table_name='$table' AND column_name='$column';";
//	error_log ("addDatabaseColumn: $sql");
		if (($sth = $dbh->query ($sql)) === false)
		{
			// Non-critical error so continue with next action.
			error_log ("Error: ".print_r($sth->errorInfo(),true)." in matts_luxury_pack - addDatabaseColumn: $sql");
		} else
		{
			$row = $sth->fetch (PDO::FETCH_ASSOC);
	if (self::$debugit)		error_log ("exists?($table.$column)...$sql in ". $mytime->took());
			if (strtolower($row['data_type']) != strtolower($type))
			{
				$sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type";
				if (!is_null($length))
				{
					$length = strtr($length, '.', ',');//str_replace('.', ',', $length);
					$sql.= '( '. $length. ' )';
				}
				$sql.= $cannull ? 			" NOT NULL" : 				" NULL";
				$sql.= isset($def_value) ? 	" DEFAULT '$def_value'" : 	"";
				$sql.= $after ? 			" AFTER `$after`" : 		"";
				$sql.= $collate ? 			" COLLATE '$collate'" : 	"";
				$sql.= ";";
	if (self::$debugit)		error_log ("add($table.$column)...$sql in ". $mytime->took());
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


	public static function log()
	{
		/********************* sql_queries section ***********************/
		/**/
/*		if (!DBcolumnExists(TB_PREFIX.'log', 'function')) {*/
/*			$sql = 'ALTER TABLE '.TB_PREFIX.'log :act :name :type';
			$sqlcol = ' COLLATE :collate :ull';
			$sqllen = ' ( :length ):ull';
			dbQuery($sql.$sqlcol,
				':act', 	'ADD',
				':name', 	'function',
				':type', 	'text',
				':collate', 'utf8_unicode_ci',
				':ull', 	'NOT NULL');//1
			dbQuery($sql.$sqlcol,
				':act', 	'ADD',
				':name', 	'file',
				':type', 	'text',
				':collate', 'utf8_unicode_ci',
				':ull', 	'NOT NULL');//2
			dbQuery($sql.$sqllen,
				':act', 	'ADD',
				':name', 	'line',
				':type', 	'INT',
				':length', 	'11',
				':ull', 	'NOT NULL');//3
			dbQuery($sql.$sqlcol,
				':act', 	'ADD',
				':name', 	'proposed',
				':type', 	'text',
				':collate', 'utf8_unicode_ci',
				':ull', 	'NOT NULL');//4
			dbQuery($sql.$sqlcol,
				':act', 	'ADD',
				':name', 	'stack',
				':type', 	'text',
				':collate', 'utf8_unicode_ci',
				':ull', 	'NOT NULL');//5
			*/
			self::addDatabaseColumn ('function', 	TB_PREFIX.'log', 'TEXT', 	null, 	false, 0, null, 'utf8_unicode_ci');
			self::addDatabaseColumn ('type', 		TB_PREFIX.'log', 'VARCHAR', 10, 	false, 0);
			self::addDatabaseColumn ('file', 		TB_PREFIX.'log', 'TEXT', 	null, 	false, 0, null, 'utf8_unicode_ci');
			self::addDatabaseColumn ('line', 		TB_PREFIX.'log', 'INT', 	11, 	false, 0, null, 'utf8_unicode_ci');
			self::addDatabaseColumn ('proposed', 	TB_PREFIX.'log', 'TEXT', 	null, 	false, 0, null, 'utf8_unicode_ci');
			self::addDatabaseColumn ('stack', 		TB_PREFIX.'log', 'TEXT', 	null, 	false, 0, null, 'utf8_unicode_ci');
/*		}*/
	}


	public static function customers()
	{
		/********************* customer section ***********************/
		$sql = 'ALTER TABLE '.TB_PREFIX.'customers :act :name :type ( :length ):ull';

		if (!DBcolumnExists(TB_PREFIX.'customers', 'credit_card_cvc')) {
			$sqldef = ' DEFAULT :def';
			$sqlaft = ' AFTER :after;';
			dbQuery($sql.$sqlaft,
				':act', 	'ADD',
				':name', 	'credit_card_cvc',
				':type', 	'INT',
				':length', 	'11',
				':ull', 	'NOT NULL',
				':after', 	'credit_card_number');
			dbQuery($sql.$sqldef,
				':act', 	'MODIFY COLUMN',
				':name', 	'credit_card_expiry_year',
				':type', 	'INT',
				':length', 	'11',
				':ull', 	'NOT NULL',
				':def', 	'NULL');
			dbQuery($sql.$sqldef,
				':act', 	'MODIFY COLUMN',
				':name', 	'credit_card_expiry_month',
				':type', 	'INT',
				':length', 	'11',
				':ull', 	'NOT NULL',
				':def', 	'NULL');
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
		if (!DBcolumnExists(TB_PREFIX.'customers', 'price_list')) {
		//	$sql = "ALTER TABLE ".TB_PREFIX."customers :act :name :type ( :length ):ull";
			dbQuery($sql,
				':act', 	'ADD COLUMN',
				':name', 	'price_list',
				':type', 	'INT',
				':length', 	'11',
				':ull', 	'NOT NULL');
		/*
			$dbh->beginTransaction();
			$sth = $dbh->exec("ALTER TABLE ".TB_PREFIX."customers ADD COLUMN price_list INT(11) NOT NULL");
			$dbh->commit();
		*/
		}
		//echo '<script>alert("matts_luxury_pack-init:113 ['.((microtime_float()-$start_time)*100).']")</script>';//135
	}


	public static function products()
	{
		/******************************** product section **************************/
		//include_once ('extensions/matts_luxury_pack/include/class/myproduct.php');

		//{if $defaults.multiple_prices}
		self::addDatabaseColumn ('unit_list_price2', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
		self::addDatabaseColumn ('unit_list_price3', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
		self::addDatabaseColumn ('unit_list_price4', TB_PREFIX.'products', 'DECIMAL', 25.6, false, 0);
		self::addDatabaseColumn ('category', TB_PREFIX.'products', 'VARCHAR', 50, false, 0);
/*
        global $pdoDb;
        if (checkFieldExists(TB_PREFIX. 'products', 	'unit_list_price2') != false)		return true;
		$domain_id = domain_id::get();
		//if (!$pdoDb->request("DROP", "expense")) 		return false;
		// @formatter:off
		$pdoDb->addTableColumns("unit_list_price2", 	"DECIMAL(25,6)", 	"NOT NULL DEFAULT 0");
		$pdoDb->addTableColumns("unit_list_price3", 	"DECIMAL(25,6)", 	"NOT NULL DEFAULT 0");
		$pdoDb->addTableColumns("unit_list_price4", 	"DECIMAL(25,6)", 	"NOT NULL DEFAULT 0");
		$pdoDb->addTableEngine("InnoDb");
		//if (!$pdoDb->request("CREATE TABLE", "products"))		return false;
		//$pdoDb->addTableConstraints("compound(domain_id, id)", 	"ADD PRIMARY KEY");
		if (!$pdoDb->request("ALTER TABLE", "products"))		return false;
		// @formatter:on
*/
		//echo '<script>alert("matts_luxury_pack-init:120 ['.((microtime_float()-$start_time)*100).']")</script>';//335
	}


	public static function invoices()
	{
		/******************************** invoice section *******************************/

		//{if $defaults.use_ship_to}
		self::addDatabaseColumn ('ship_to_customer_id', TB_PREFIX.'invoices', 'int', 11, false, 0, 'customer_id');
		//{if $defaults.use_terms}
		self::addDatabaseColumn ('terms', TB_PREFIX.'invoices', 'VARCHAR', 100);
		//echo '<script>alert("matts_luxury_pack-init:126 ['.((microtime_float()-$start_time)*100).']")</script>';//500
/*
        global $pdoDb;
        $domain_id = domain_id::get();
		// @formatter:off
        if (checkFieldExists(TB_PREFIX. 'invoices', 		'ship_to_customer_id') != true)
			$pdoDb->addTableColumns("ship_to_customer_id", 	"INT(11)", 	"NOT NULL DEFAULT 0 AFTER customer_id");
        if (checkFieldExists(TB_PREFIX. 'invoices', 		'ship_to_customer_id') != true)
			$pdoDb->addTableColumns("terms", 				"VARCHAR(100)", "NOT NULL");
		$pdoDb->addTableEngine("InnoDb");
		//if (!$pdoDb->request("CREATE TABLE", "invoices"))		return false;
		//$pdoDb->addTableConstraints("compound(domain_id, id)", 	"ADD PRIMARY KEY");
		if (!$pdoDb->request("ALTER TABLE", "invoices"))		return false;
		// @formatter:on
*/

//ALTER TABLE `si_invoices` ADD `attention` VARCHAR( 50 ) NOT NULL ;
		self::addDatabaseColumn ('attention', TB_PREFIX.'invoices', 'VARCHAR', 50, false, 0);
	}


	public static function payments()
	{
		/****************************** payments section *********************************/
	}

}
