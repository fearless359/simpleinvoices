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
        $rows = self::getExpenses();
        return count($rows);
    }

    /**
     * Get a specified expense record.
     * @param int $id of expense record to retrieve.
     * @return array Expense record.
     */
    public static function getOne($id) {
        $rows = self::getExpenses($id);
        return (empty($rows) ? array() : $rows[0]);
    }

    /**
     * Get all expense records.
     * @return array $rows of expense records.
     */
    public static function getAll() {
        return self::getExpenses();
    }

    /**
     * Get all expense records.
     * @param int $id If not null, id of record to retrieve.
     * @return array $rows of expense records.
     */
    private static function getExpenses($id = null) {
        global $LANG, $pdoDb;

        $expenses = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('e.id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

            $join = new Join("LEFT", "expense_account", "ea");
            $join->addSimpleItem("ea.id", new DbField("e.expense_account_id"), "AND");
            $join->addSimpleItem("ea.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "biller", "b");
            $join->addSimpleItem("b.id", new DbField("e.biller_id"), "AND");
            $join->addSimpleItem("b.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "customers", "c");
            $join->addSimpleItem("c.id", new DbField("e.customer_id"), "AND");
            $join->addSimpleItem("c.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "products", "p");
            $join->addSimpleItem("p.id", new DbField("e.product_id"), "AND");
            $join->addSimpleItem("p.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "invoices", "i");
            $join->addSimpleItem("i.id", new DbField("e.invoice_id"), "AND");
            $join->addSimpleItem("i.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $case = new CaseStmt("status", "status_wording");
            $case->addWhen("=", ENABLED, $LANG['paid']);
            $case->addWhen("!=", ENABLED, $LANG['not_paid'], true);
            $pdoDb->addToCaseStmts($case);

            // This is a fudge until sub-select can be added to the features.
            $exp_item_tax = TB_PREFIX . "expense_item_tax";
            $pdoDb->addToFunctions("(SELECT SUM(tax_amount) FROM $exp_item_tax WHERE expense_id = id) AS tax");
            $pdoDb->addToFunctions("(SELECT tax + e.amount) AS total");

            $selectList = array(
                new DbField('e.id', 'EID'),
                new DbField('e.domain_id', 'domain_id'),
                new DbField('e.status', 'status'),
                new DbField('e.amount', 'amount'),
                new DbField('e.date', 'date'),
                new DbField('e.note', 'note'),
                new DbField('e.expense_account_id', 'ea_id'),
                new DbField('e.biller_id', 'b_id'),
                new DbField('e.customer_id', 'c_id'),
                new DbField('e.product_id', 'p_id'),
                new DbField('i.id', 'iv_id'),
                new DbField('b.name', 'b_name'),
                new DbField('ea.name', 'ea_name'),
                new DbField('c.name', 'c_name'),
                new DbField('p.description', 'p_desc')
            );
            $pdoDb->setSelectList($selectList);

            $rows = $pdoDb->request("SELECT", "expense", 'e');
            foreach($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['p_desc'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['p_desc'];
                $row['status_wording'] = ($row['status'] == ENABLED ? $LANG['paid'] : $LANG['not_paid']);
                $expenses[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Expense::getExpense() - error: " . $pde->getMessage());
        }
        return $expenses;
    }

    /**
     * Get information needed when adding a new expense.
     * @return array Containing the keys: expense_accounts, customers, billers, invoices, products.
     */
    public static function additionalInfo() {
        // @formatter:off
        $add_info = array();
        $add_info['expense_accounts'] = ExpenseAccount::getAll();
        $add_info['customers']        = Customer::getAll(['enabled_only' => true]);
        $add_info['billers']          = Biller::getAll();
        $add_info['invoices']         = Invoice::getAll();
        $add_info['products']         = Product::getAll(true);
        // @formatter:on
        return $add_info;
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
                    $tax = Taxes::getOne($value);

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
     * @return mixed $rows for normal selection and calculated count for $type is 'count'.
     */
    public static function xmlSql()
    {
        global $LANG, $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

            $join = new Join("LEFT", "expense_account", "ea");
            $join->addSimpleItem("ea.id", new DbField("e.expense_account_id"), "AND");
            $join->addSimpleItem("ea.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "biller", "b");
            $join->addSimpleItem("b.id", new DbField("e.biller_id"), "AND");
            $join->addSimpleItem("b.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "customers", "c");
            $join->addSimpleItem("c.id", new DbField("e.customer_id"), "AND");
            $join->addSimpleItem("c.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "products", "p");
            $join->addSimpleItem("p.id", new DbField("e.product_id"), "AND");
            $join->addSimpleItem("p.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

            $join = new Join("LEFT", "invoices", "i");
            $join->addSimpleItem("i.id", new DbField("e.invoice_id"), "AND");
            $join->addSimpleItem("i.domain_id", new DbField("e.domain_id"));
            $pdoDb->addToJoins($join);

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
