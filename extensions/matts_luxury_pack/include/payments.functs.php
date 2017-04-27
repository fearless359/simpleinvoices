<?php

/****************************** payments section *********************************/
function paymentssql($type = '', $dir, $sort, $rp, $page, $querystr='') {
    global $LANG, $pdoDb;

//$pdoDb->debugOn();
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $pdoDb->addSimpleWhere("ap.ac_inv_id", $id, "AND");
    } elseif (!empty($_GET['c_id'])) {
        $id = $_GET['c_id'];
        $pdoDb->addSimpleWhere("c.id", $id, "AND");
    }
    
    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!empty($qtype) && !empty($query)) {
        $valid_search_fields = array('ap.id', 'b.name', 'c.name', 'iv.index_id');
        if (in_array ($qtype, $valid_search_fields) ) {
            $pdoDb->addSimpleWhere($qtype, "%$query%", "AND");
        }
    }
    $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());

    $jn = new Join("INNER", "invoices", "iv");
    $oc = new OnClause();
    $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
    $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "customers", "c");
    $oc = new OnClause();
    $oc->addSimpleItem("c.id", new DbField("iv.customer_id"), "AND");
    $oc->addSimpleItem("c.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "biller", "b");
    $oc = new OnClause();
    $oc->addSimpleItem("b.id", new DbField("iv.biller_id"), "AND");
    $oc->addSimpleItem("b.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);
    
    if ($type =="count") {
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "payment", "ap");
        return $rows[0]['count'];
    }

    $jn = new Join("INNER", "preferences", "pr");
    $oc = new OnClause();
    $oc->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
    $oc->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "payment_types", "pt");
    $oc = new OnClause();
    $oc->addSimpleItem("pt.pt_id", new DbField("ap.ac_payment_type"), "AND");
    $oc->addSimpleItem("pt.domain_id", new DbField("ap.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);
    
    $start = (($page-1) * $rp);
    $pdoDb->setLimit($rp, $start);

    if (in_array($sort, array('ap.id', 'ap.ac_inv_id', 'description'))) {
        if (!preg_match('/^(asc|desc)$/iD', $dir)) $dir = 'D';
        $oc = new OrderBy($sort, $dir);
    } else {
        $oc = new OrderBy("description");
    }

    $fn = new FunctionStmt("DATE_FORMAT", "ac_date,'%Y-%m-%d'");
    $se = new Select($fn, null, null, "date");
    $pdoDb->addToSelectStmts($se);

    $pdoDb->setOrderBy($oc);

    $list = array("ap.*", "c.name as cname", "b.name as bname", "pt.pt_description AS description",
                  "ap.ac_notes AS notes");
    $pdoDb->setSelectList($list);
    
    $fn = new FunctionStmt("CONCAT", "pr.pref_inv_wording,' ',iv.index_id");
    $se = new Select($fn, null, null, "index_name");
    $pdoDb->addToSelectStmts($se);

    $result = $pdoDb->request("SELECT", "payment", "ap");
    return $result;
}
