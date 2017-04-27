<?php

$cc_months = array ();
$cc_months[] = (isset($LANG['select']))	? $LANG['select'] 	: "--";
$cc_months[] = "01 ".((isset($LANG['jan']))	? $LANG['jan'] 	: "Jan");
$cc_months[] = "02 ".((isset($LANG['feb']))	? $LANG['feb'] 	: "Feb");
$cc_months[] = "03 ".((isset($LANG['mar']))	? $LANG['mar'] 	: "Mar");
$cc_months[] = "04 ".((isset($LANG['apr']))	? $LANG['apr'] 	: "Apr");
$cc_months[] = "05 ".((isset($LANG['may']))	? $LANG['may'] 	: "May");
$cc_months[] = "06 ".((isset($LANG['jun']))	? $LANG['jun'] 	: "Jun");
$cc_months[] = "07 ".((isset($LANG['jul']))	? $LANG['jul'] 	: "Jul");
$cc_months[] = "08 ".((isset($LANG['aug']))	? $LANG['aug'] 	: "Aug");
$cc_months[] = "09 ".((isset($LANG['sep']))	? $LANG['sep'] 	: "Sep");
$cc_months[] = "10 ".((isset($LANG['oct']))	? $LANG['oct'] 	: "Oct");
$cc_months[] = "11 ".((isset($LANG['nov']))	? $LANG['nov'] 	: "Nov");
$cc_months[] = "12 ".((isset($LANG['dec']))	? $LANG['dec'] 	: "Dec");
$cc_years = array();
$cc_years[] = isset($LANG['select'])	? $LANG['select'] 	: "--";
for ($i=0; $i<6; $i++) {
	$cc_years[] = date("Y")+$i;
}
/********************* customer section ***********************/
function update_Customer() {
	global $config;
	$domain_id = domain_id::get();

//	$encrypted_credit_card_number = '';
	$is_new_cc_num = ($_POST['credit_card_number_new'] !='');

	$sql = "UPDATE
				".TB_PREFIX."customers
			SET
				domain_id = :domain_id,
				name = :name,
				attention = :attention,
				street_address = :street_address,
				street_address2 = :street_address2,
				city = :city,
				state = :state,
				zip_code = :zip_code,
				country = :country,
				phone = :phone,
				mobile_phone = :mobile_phone,
				fax = :fax,
				email = :email,
				credit_card_holder_name = :credit_card_holder_name,
                " . (($is_new_cc_num) ? 'credit_card_number = :credit_card_number,' : '') . "
				credit_card_expiry_month = :credit_card_expiry_month,
				credit_card_expiry_year = :credit_card_expiry_year,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				price_list = :price_list,
				enabled = :enabled
			WHERE
				id = :id";

	if($is_new_cc_num)
	{
		$credit_card_number = $_POST['credit_card_number_new'];
        
        //cc
        $enc = new Encryption();//encryption();
        $key = $config->encryption->default->key;	
        $encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

		return dbQuery($sql,
			':domain_id', $domain_id,
			':name', $_POST['name'],
			':attention', $_POST['attention'],
			':street_address', $_POST['street_address'],
			':street_address2', $_POST['street_address2'],
			':city', $_POST['city'],
			':state', $_POST['state'],
			':zip_code', $_POST['zip_code'],
			':country', $_POST['country'],
			':phone', $_POST['phone'],
			':mobile_phone', $_POST['mobile_phone'],
			':fax', $_POST['fax'],
			':email', $_POST['email'],
			':notes', $_POST['notes'],
			':credit_card_holder_name', $_POST['credit_card_holder_name'],
			':credit_card_number', $encrypted_credit_card_number,
			':credit_card_expiry_month', $_POST['credit_card_expiry_month'],
			':credit_card_expiry_year', $_POST['credit_card_expiry_year'],
			':custom_field1', $_POST['custom_field1'],
			':custom_field2', $_POST['custom_field2'],
			':custom_field3', $_POST['custom_field3'],
			':custom_field4', $_POST['custom_field4'],
			':price_list', $_POST['price_list'],
			':enabled', $_POST['enabled'],
			':id', $_GET['id']
		);
	} else {
		return dbQuery($sql,
			':domain_id', $domain_id,
			':name', $_POST['name'],
			':attention', $_POST['attention'],
			':street_address', $_POST['street_address'],
			':street_address2', $_POST['street_address2'],
			':city', $_POST['city'],
			':state', $_POST['state'],
			':zip_code', $_POST['zip_code'],
			':country', $_POST['country'],
			':phone', $_POST['phone'],
			':mobile_phone', $_POST['mobile_phone'],
			':fax', $_POST['fax'],
			':email', $_POST['email'],
			':notes', $_POST['notes'],
			':credit_card_holder_name', $_POST['credit_card_holder_name'],
			':credit_card_expiry_month', $_POST['credit_card_expiry_month'],
			':credit_card_expiry_year', $_POST['credit_card_expiry_year'],
			':custom_field1', $_POST['custom_field1'],
			':custom_field2', $_POST['custom_field2'],
			':custom_field3', $_POST['custom_field3'],
			':custom_field4', $_POST['custom_field4'],
			':price_list', $_POST['price_list'],
			':enabled', $_POST['enabled'],
			':id', $_GET['id']
		);
	}
}
/********************* customer section ***********************/
function insert_Customer() {
    global $config;
	$domain_id = domain_id::get();

	extract ($_POST);
	$sql = "INSERT INTO 
			".TB_PREFIX."customers
			(
				domain_id, attention, name, street_address, street_address2,
				city, state, zip_code, country, phone, mobile_phone,
				fax, email, notes,
				credit_card_holder_name, credit_card_number,
				credit_card_expiry_month, credit_card_expiry_year, 
				custom_field1, custom_field2,
				custom_field3, custom_field4, price_list, enabled
			)
			VALUES 
			(
				:domain_id ,:attention, :name, :street_address, :street_address2,
				:city, :state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :notes, 
				:credit_card_holder_name, :credit_card_number,
				:credit_card_expiry_month, :credit_card_expiry_year, 
				:custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :price_list, :enabled
			)";
	//cc
	$enc = new Encryption();//encryption();
    $key = $config->encryption->default->key;	
	$encrypted_credit_card_number = $enc->encrypt ($key, $credit_card_number);

	return dbQuery ($sql,
		':attention', $attention,
		':name', $name,
		':street_address', $street_address,
		':street_address2', $street_address2,
		':city', $city,
		':state', $state,
		':zip_code', $zip_code,
		':country', $country,
		':phone', $phone,
		':mobile_phone', $mobile_phone,
		':fax', $fax,
		':email', $email,
		':notes', $notes,
		':credit_card_holder_name', $credit_card_holder_name,
		':credit_card_number', $encrypted_credit_card_number,
		':credit_card_expiry_month', $credit_card_expiry_month,
		':credit_card_expiry_year', $credit_card_expiry_year,
		':custom_field1', $custom_field1,
		':custom_field2', $custom_field2,
		':custom_field3', $custom_field3,
		':custom_field4', $custom_field4,
		':price_list', $price_list,
		':enabled', $enabled,
		':domain_id', $domain_id
		);
}
/********************* customer section ***********************/
function customersql($type = '', $start, $dir, $sort, $rp, $page) {
    global $LANG, $pdoDb;

    $valid_search_fields = array('c.id', 'c.name');

    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!empty($qtype) && !empty($query)) {
        if ( in_array($qtype, $valid_search_fields) ) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
        }
    }
    $pdoDb->addSimpleWhere("c.domain_id", domain_id::get());

    if($type =="count") {
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "customers", "c");
        return $rows[0]['count'];
    }

    if (intval($page) != $page) $start = 0;
    if (intval($rp)   != $rp  ) $rp = 25;

    $start = (($page - 1) * $rp);

    $pdoDb->setSelectList(array("c.id as CID", "c.name", "c.enabled", "c.street_address", "c.attention"));

    $case = new CaseStmt("c.enabled", "enabled_txt");
    $case->addWhen( "=", ENABLED, $LANG['enabled']);
    $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
    $pdoDb->addToCaseStmts($case);

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
        $sortlist = array(array("enabled", "D"), array($sort, $dir));
    } else {
        $sortlist = array(array("enabled", "D"), array("name", "A"));
    }
    $pdoDb->setOrderBy($sortlist);

    $pdoDb->setGroupBy("CID");

    $pdoDb->setLimit($rp, $start);

    $result = $pdoDb->request("SELECT", "customers", "c");
    return $result;
}
