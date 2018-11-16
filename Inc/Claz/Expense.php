<?php
namespace Inc\Claz;

/**
 * Class Expense
 * @package Inc\Claz
 */
class Expense {

    /**
     * @return int count of expense records.
     */
    public static function count() {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->addToFunctions("count(`id`) as count");
            $rows = $pdoDb->request("SELECT", "expense");
            $count = $rows[0]['count'];
        } catch (PdoDbException $pde) {
            error_log("Expense::count() - error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * Get all expense records.
     * @return array $rows of expense records.
     */
    public static function getAll() {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setOrderBy("id");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "expense");
        } catch (PdoDbException $pde) {
            error_log("Expense::getAll() - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Get information needed when adding a new expense.
     * @return array Containing the keys: expense_accounts, customers, billers, invoices, products.
     */
    public static function addInfo() {
        // @formatter:off
        $add_info = array();
        $add_info['expense_accounts'] = ExpenseAccount::getAll();
        $add_info['customers']        = Customer::get_all(true);
        $add_info['billers']          = Biller::getAll();
        $add_info['invoices']         = Invoice::get_all();
        $add_info['products']         = Product::select_all();
        // @formatter:on
        return $add_info;
    }

    /**
     * Get a specified expense record.
     * @param int $id of expense record to retrieve.
     * @return array Expense record.
     */
    public static function get($id) {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get(), "AND");
            $pdoDb->addSimpleWhere("id", $id);
            $rows = $pdoDb->request("SELECT", "expense");
        } catch (PdoDbException $pde) {
            error_log("Expense::get() - id[$id] error: " . $pde->getMessage());
        }
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * Get information needed when updating an expense.
     * @return array Containing the keys: expense_accounts, customers, billers, invoices, products.
     */
    public static function detailInfo() {
        // @formatter:off
        $detail_info = array();
        $detail_info['expense_accounts'] = ExpenseAccount::getAll();
        $detail_info['customers']        = Customer::get_all(true);
        $detail_info['billers']          = Biller::getAll();
        $detail_info['invoices']         = Invoice::get_all();
        $detail_info['products']         = Product::select_all();
        // @formatter:on
        return $detail_info;
    }

    /**
     * Insert a new expense record.
     * @return bool true if record inserted correctly; false if not.
     */
    public static function insert() {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "expense");

            $line_item_tax_id = (isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "");
            self::expenseItemTax($id, $line_item_tax_id, $_POST['amount'], "1", "insert");
        } catch (PdoDbException $pde) {
            error_log("Expense::insert() - error: " . $pde->getMessage() . " _POST info - " . print_r($_POST,true));
            return false;
        }
        return true;
    }

    /**
     * Update an expense record.
     * @return bool true if update succeeded; false if not.
     */
    public static function update() {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->addSimpleWhere("domain_id", $_POST['domain_id'], "AND");
            $pdoDb->addSimpleWhere("id", $_GET['id']);
            if (!$pdoDb->request("UPDATE", "expense")) return false;

            $line_item_tax_id = (isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "");
            self::expenseItemTax($_GET['id'], $line_item_tax_id, $_POST['amount'], "1", "update");
        } catch (PdoDbException $pde) {
            error_log("Expense::update() - id[{$_GET['id']}] error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Insert/update the multiple taxes per line item into the si_expense_item_tax table
     * @param int $expense_id
     * @param int $line_item_tax_id
     * @param float $unit_price
     * @param int $quantity
     * @param string $action
     * @return bool true if processed without error; false otherwise.
     */
    public static function expenseItemTax($expense_id, $line_item_tax_id, $unit_price, $quantity, $action="") {
        if (!is_array($line_item_tax_id)) return false;

        // if editing invoice delete all tax info then insert updated info.
        try {
            $requests = new Requests();
            if ($action == "update") {
                $request = new Request("DELETE", "expense_item_tax");
                $request->addSimpleWhere("expense_id", $expense_id);
            }

            foreach($line_item_tax_id as $value) {
                if($value !== "") {
                    $tax = Taxes::getTaxRate($value);

                    $tax_amount = Taxes::lineItemTaxCalc($tax, $unit_price,$quantity);

                    $request = new Request("INSERT", "expense_item_tax");
                    $request->setExcludedFields("id");
                    $request->setFauxPost(array("expense_id" => $expense_id,
                                                "tax_id"     => $tax['tax_id'],
                                                "tax_type"   => $tax['type'],
                                                "tax_rate"   => $tax['tax_percentage'],
                                                "tax_amount" => $tax_amount));
                    $requests->add($request);
                }
            }
            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Expense::expenseItemTax(): Unable to process requests. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Select record for the flexigrid list of expenses.
     * @param string $type set to 'count' if a count of all qualified records will be calculated.
     * @param string $dir sort direction A, ASC, D or DESC work.
     * @param string $sort field to order by subject to $dir setting.
     * @param int $rp number of records per page to display. Ignored if $type is "count".
     * @param int $page number used with $rp to calculate next page ($rp) set of records to select.
     *          Ignored if $type is "count".
     * @return mixed $rows for normal selection and calculated count for $type is 'count'.
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page)
    {
        global $LANG, $pdoDb;

        $get_count = ($type == 'count');
        $rows = array();
        try {
            $table_alias = "";
            $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
            $qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
            if (!(empty($qtype) || empty($query))) {
                // @formatter:off
                if (in_array($qtype, array( 'e.id',
                                            'ea.name',
                                            'b.name',
                                            'c.name',
                                            'i.id',
                                            'e.status_wording')
                )) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
                    $table_alias = strstr($qtype, ".", true);
                }
                // @formatter:on
            }

            $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

            // If getting a count, only include joins if they are selected by the query.
            if (!$get_count || $table_alias == "ea") {
                $join = new Join("LEFT", "expense_account", "ea");
                $join->addSimpleItem("ea.id", new DbField("e.expense_account_id"), "AND");
                $join->addSimpleItem("ea.domain_id", new DbField("e.domain_id"));
                $pdoDb->addToJoins($join);
            }

            if (!$get_count || $table_alias == "b") {
                $join = new Join("LEFT", "biller", "b");
                $join->addSimpleItem("b.id", new DbField("e.biller_id"), "AND");
                $join->addSimpleItem("b.domain_id", new DbField("e.domain_id"));
                $pdoDb->addToJoins($join);
            }

            if (!$get_count || $table_alias == "c") {
                $join = new Join("LEFT", "customers", "c");
                $join->addSimpleItem("c.id", new DbField("e.customer_id"), "AND");
                $join->addSimpleItem("c.domain_id", new DbField("e.domain_id"));
                $pdoDb->addToJoins($join);
            }

            if (!$get_count || $table_alias == "p") {
                $join = new Join("LEFT", "products", "p");
                $join->addSimpleItem("p.id", new DbField("e.product_id"), "AND");
                $join->addSimpleItem("p.domain_id", new DbField("e.domain_id"));
                $pdoDb->addToJoins($join);
            }

            if (!$get_count || $table_alias == "i") {
                $join = new Join("LEFT", "invoices", "i");
                $join->addSimpleItem("i.id", new DbField("e.invoice_id"), "AND");
                $join->addSimpleItem("i.domain_id", new DbField("e.domain_id"));
                $pdoDb->addToJoins($join);
            }

            if ($get_count) {
                $pdoDb->addToFunctions("count(*) AS count");
                $rows = $pdoDb->request("SELECT", "expense", "e");
                return $rows[0]['count'];
            }

            if (intval($page) != $page) $page = 1;
            if (intval($rp) != $rp) $rp = 25;

            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            // @formatter:off
            if (!in_array($sort, array( 'id',
                                        'status',
                                        'amount',
                                        'expense_account_id',
                                        'biller_id',
                                        'customer_id',
                                        'invoice_id',
                                        'date',
                                        'amount',
                                        'note')
            )) {
                $sort = "id";
            }
            // @formatter:on

            $dir = (preg_match('/^(a|d|asc|desc)$/iD', $dir) ? 'A' : 'D');
            $pdoDb->setOrderBy(array($sort, $dir));

            $case = new CaseStmt("status", "status_wording");
            $case->addWhen("=", ENABLED, $LANG['paid']);
            $case->addWhen("!=", ENABLED, $LANG['not_paid'], true);
            $pdoDb->addToCaseStmts($case);

            // This is a fudge until sub-select can be added to the features.
            $exp_item_tax = TB_PREFIX . "expense_item_tax";
            $pdoDb->addToFunctions("(SELECT SUM(tax_amount) FROM $exp_item_tax WHERE expense_id = id) AS tax");
            $pdoDb->addToFunctions("(SELECT tax + e.amount) AS total");

            // @formatter:off
            $selectList = array("e.id AS EID",
                                "e.status AS status",
                                "e.*",
                                "i.id AS iv_id",
                                "b.name AS b_name",
                                "ea.name AS ea_name",
                                "c.name AS c_name",
                                "p.description AS p_desc");
            // @formatter:on
            $pdoDb->setSelectList($selectList);

            $rows = $pdoDb->request("SELECT", "expense", "e");
        } catch (PdoDbException $pde) {
            error_log("Expense::sql() - error: " . $pde->getMessage());
            if ($get_count) {
                return 0;
            }
        }

        return $rows;
    }

}
