<?php
/*
 * Script: ./extensions/matts_luxury_pack/include/class/mycustomers.php
 * 	product class
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

class mycustomers extends Customer
{
	public static function enable ($domain_id='')
	{
		$sql = "UPDATE ".TB_PREFIX."customers
				SET	enabled = '1'
				WHERE	id = :id";

	//	echo "<script>alert('sql=$sql')</script>";
		return dbQuery ($sql,
			':id', 				$_GET['id']
		);
	}


	public static function disable ($domain_id='')
	{
		$sql = "UPDATE ".TB_PREFIX."customers
				SET	enabled = '0'
				WHERE	id = :id";

	//	echo "<script>alert('sql=$sql')</script>";
		return dbQuery ($sql,
			':id', 				$_GET['id']
		);
	}


	public static function sql ($type = '', $start, $dir, $sort, $rp, $page)
	{
		global $LANG, $pdoDb;

		$valid_search_fields = array('c.id', 'c.name', 'c.attention', 'c.street_address', 'c.phone');

		$query = isset($_POST['query']) ? $_POST['query'] : null;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
		if (!empty($qtype) && !empty($query)) {
			if ( in_array($qtype, $valid_search_fields) ) {
				$pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
			}
		}
		$pdoDb->addSimpleWhere("c.domain_id", domain_id::get());

		if ($type =="count") {
			$pdoDb->addToFunctions("COUNT(*) AS count");
			$rows = $pdoDb->request("SELECT", "customers", "c");
			return $rows[0]['count'];
		}
		if (intval($page) != $page) $start = 0;
		if (intval($rp)   != $rp  ) $rp = 25;
		$start = (($page - 1) * $rp);

		$pdoDb->setSelectList(array("c.id as CID", "c.name", "c.enabled", "c.street_address", "c.attention"));//Matt

		$case = new CaseStmt("c.enabled", "enabled_txt");
		$case->addWhen( "=", ENABLED, $LANG['enabled']);
		$case->addWhen("!=", ENABLED, $LANG['disabled'], true);
//		$pdoDb->addToCaseStmts($case);

		$fn = new FunctionStmt("COALESCE", "SUM(ii.total), 0", "total");
		$fr = new FromStmt("invoice_items", "ii");
		$jn = new Join("INNER", "invoices", "iv");
		$oc = new OnClause();
		$oc->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
		$oc->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
		$jn->setOnClause($oc);
		$wh = new WhereClause();
		$wh->addSimpleItem("iv.customer_id", new DbField("CID"), "AND");
		$wh->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
		$se = new Select($fn, $fr, $wh, "customer_total");
		$se->addJoin($jn);
		$pdoDb->addToSelectStmts($se);

		$fn = new FunctionStmt("COALESCE", "SUM(ap.ac_amount), 0", "amount");
		$fr = new FromStmt("payment", "ap");
		$jn = new Join("INNER", "invoices", "iv");
		$oc = new OnClause();
		$oc->addSimpleItem("iv.id", new DbField("ap.ac_inv_id"), "AND");
		$oc->addSimpleItem("iv.domain_id", new DbField("ap.domain_id"));
		$jn->setOnClause($oc);
		$wh = new WhereClause();
		$wh->addSimpleItem("iv.customer_id", new DbField("CID"), "AND");
		$wh->addSimpleItem("iv.domain_id", new DbField("ap.domain_id"));
		$se = new Select($fn, $fr, $wh, "paid");
		$se->addJoin($jn);
		$pdoDb->addToSelectStmts($se);

		$fn = new FunctionStmt(null, "customer_total");
		$fn->addPart("-", "paid");
		$se = new Select($fn, null, null, "owing");
		$pdoDb->addToSelectStmts($se);

		$validFields = array('CID', 'name', 'customer_total', 'paid', 'owing', 'enabled');
		if (in_array($sort, $validFields)) {
			$dir = (preg_match('/^(asc|desc)$/iD', $dir) ? 'A' : 'D');
//			$sortlist = array(array("enabled", "D"), array($sort, $dir));
			$sortlist = array(array($sort, $dir));
		} else {
//			$sortlist = array(array("enabled", "D"), array("name", "A"));
			$sortlist = array(array("name", "A"));
		}
		$pdoDb->setOrderBy($sortlist);

		$pdoDb->setGroupBy("CID");

		$pdoDb->setLimit($rp, $start);

		$result = $pdoDb->request("SELECT", "customers", "c");
		return $result;
	}
}
