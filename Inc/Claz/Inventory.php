<?php
namespace Inc\Claz;

/**
 * Class Inventory
 */
class Inventory {

    /**
     * @return int
     */
    public static function count() {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "inventory");
            if (!empty($rows)) {
                $count = $rows[0]['count'];
            }
        } catch (PdoDbException $pde) {
            error_log("Inventory::count() - Error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * @return array|mixed
     */
    public static function select() {
        global $pdoDb;

        $rows = array();
        try {
            $join = new Join("LEFT", "inventory", "iv");
            $join->addSimpleItem("p.id", new DbField("iv.product_id"), "AND");
            $join->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($join);

            $pdoDb->addSimpleWhere("iv.domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("iv.id", $_GET['id']);

            $pdoDb->setSelectList(array("iv.*", "p.description"));
            $rows = $pdoDb->request("SELECT", "products", "p");
        } catch (PdoDbException $pde) {
            error_log("Inventory::select(): Error: " . $pde->getMessage());
        }
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * @param $type
     * @param $sort
     * @param $dir
     * @param $rp
     * @param $page
     * @return array|mixed
     */
    public static function xmlSql($type, $sort, $dir, $rp, $page) {
        global $pdoDb;

        $rows = array();
        try {
            $query = isset ($_POST ['query']) ? $_POST ['query'] : null;
            $qtype = isset ($_POST ['qtype']) ? $_POST ['qtype'] : null;
            if (!empty($qtype) && !empty($query)) {
                if (in_array($qtype, array('p.description', 'iv.date', 'iv.quantity', 'iv.cost', 'total_cost'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("inv.domain_id", DomainId::get());

            $oc = new OnClause();
            $oc->addSimpleItem("p.id", new DbField("inv.product_id"), "AND");
            $oc->addSimpleItem("p.domain_id", new DbField("inv.domain_id"));
            $pdoDb->addToJoins(array("LEFT", "inventory", "inv", $oc));

            if ($type == "count") {
                $pdoDb->addToFunctions("COUNT(*) AS count");
                $rows = $pdoDb->request("SELECT", "products", "p");
                return $rows[0]['count'];
            }

            if (empty($sort)) {
                $pdoDb->setOrderBy("p.id");
            } else {
                $pdoDb->setOrderBy(array($sort, $dir));
            }

            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            $pdoDb->addToFunctions("COALESCE(p.reorder_level,0) AS reorder_level");
            $pdoDb->addToFunctions("COALESCE(inv.quantity * inv.cost,0) AS total_cost");
            $expr_list = array(
                new DbField("inv.id", "id"),
                "inv.product_id",
                "inv.date",
                "inv.quantity",
                "p.description",
                "inv.cost");
            $pdoDb->setSelectList($expr_list);
            $pdoDb->setGroupBy($expr_list);

            $rows = $pdoDb->request("SELECT", "products", "p");
        } catch (PdoDbException $pde) {
            error_log("Inventory::xmlSql() - Error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * @return int|mixed
     */
    public static function insert() {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $result = $pdoDb->request("INSERT", "inventory");
        } catch (PdoDbException $pde) {
            error_log("Inventory::insert(): Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @return bool|mixed
     */
    public static function update() {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $result = $pdoDb->request("UPDATE", "inventory");
        } catch (PdoDbException $pde) {
            error_log("Inventory::update() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @throws \Exception
     */
    public static function delete() {
        throw new \Exception("inventory.php delete(): delete not supported.");
    }

    /**
     * @return array
     */
    public static function check_reorder_level() {
        $rows = Product::xmlSql('count',"","","","");
        $result = array();
        $email_message = "";
        foreach ( $rows as $row ) {
            if ($row['quantity'] <= $row['reorder_level']) {
                $message = "The quantity of Product: $row[description] is " .
                           SiLocal::number($row['quantity']) .
                           ", which is equal to or below its reorder level of $row[reorder_level]";
                $result['row_$row[id]']['message'] = $message;
                $email_message .= $message . "<br />\n";
            }
        }

        $email = new Email ();
        $email->notes   = $email_message;
        $email->from    = $email->getAdminEmail();
        $email->to      = $email->getAdminEmail();
        $email->subject = "SimpleInvoices reorder level email";
        $email->send ();

        return $result;
    }
}
