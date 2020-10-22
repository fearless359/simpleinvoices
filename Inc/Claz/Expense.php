<?php

namespace Inc\Claz;

/**
 * Class Expense
 * @package Inc\Claz
 */
class Expense
{

    /**
     * Get count of the number of expense records.
     * @return int count of expense records.
     */
    public static function count(): int
    {
        $rows = self::getExpenses();
        return count($rows);
    }

    /**
     * Get a specified expense record.
     * @param int $id of expense record to retrieve.
     * @return array Expense record.
     */
    public static function getOne(int $id): array
    {
        $rows = self::getExpenses($id);
        return empty($rows) ? [] : $rows[0];
    }

    /**
     * Get all expense records.
     * @return array $rows of expense records.
     */
    public static function getAll(): array
    {
        return self::getExpenses();
    }

    /**
     * Get all expense records.
     * @param int|null $id ID of record to retrieve, Omit to get all records.
     * @return array $rows of expense records.
     */
    private static function getExpenses(?int $id = null): array
    {
        global $LANG, $pdoDb;

        $expenses = [];
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
            $case->addWhen("=", ENABLED, $LANG['paidUc']);
            $case->addWhen("!=", ENABLED, $LANG['notPaid'], true);
            $pdoDb->addToCaseStmts($case);

            // This is a fudge until sub-select can be added to the features.
            $expItemTax = TB_PREFIX . "expense_item_tax";
            $pdoDb->addToFunctions("(SELECT SUM(tax_amount) FROM $expItemTax WHERE expense_id = e.id) AS tax");
            $pdoDb->addToFunctions("(SELECT tax + e.amount) AS total");

            $selectList = [
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
                new DbField('i.index_id', 'iv_index_id'),
                new DbField('b.name', 'b_name'),
                new DbField('ea.name', 'ea_name'),
                new DbField('c.name', 'c_name'),
                new DbField('p.description', 'p_desc')
            ];
            $pdoDb->setSelectList($selectList);

            $rows = $pdoDb->request("SELECT", "expense", 'e');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['p_desc'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['p_desc'];
                $row['status_wording'] = $row['status'] == ENABLED ? $LANG['paidUc'] : $LANG['notPaid'];
                $expenses[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Expense::getExpense() - error: " . $pde->getMessage());
        }

        return $expenses;
    }

    /**
     * Get information needed when adding a new expense.
     * @param array|null $expense record.
     * @param bool $enabledOnly true if only enabled billers and customers should be retrieved,
     *                          false if all billers and customers should be retrieved (default).
     * @return array Containing the keys: expense_accounts, customers, billers, invoices, products.
     * @throws PdoDbException
     */
    public static function additionalInfo(?array $expense=null, $enabledOnly = false): array
    {
        // @formatter:off
        $addInfo = [];
        if (isset($expense)) {
            if (isset($expense['c_id'])) {
                $addInfo['customer'] = Customer::getOne($expense['c_id']);
            }
            if (isset($expense['b_id'])) {
                $addInfo['biller'] = Biller::getOne($expense['b_id']);
            }
            if (isset($expense['iv_id'])) {
                $addInfo['invoice'] = Invoice::getOne($expense['iv_id']);
            }
            if (isset($expense['p_id'])) {
                $addInfo['product'] = Product::getOne($expense['p_id']);
            }
            if (isset($expense['ea_id'])) {
                $addInfo['expense_account'] = ExpenseAccount::getOne($expense['ea_id']);
            }
        } else {
            $params = $enabledOnly ? ['enabledOnly' => 1] : [];
            $addInfo['customers']        = Customer::getAll($params);
            $addInfo['billers']          = Biller::getAll($enabledOnly);
            $addInfo['invoices']         = Invoice::getAll();
            $addInfo['products']         = Product::getAll(true);
            $addInfo['expense_accounts'] = ExpenseAccount::getAll();
        }
        // @formatter:on
        return $addInfo;
    }

    /**
     * Insert a new expense record.
     * @return bool true if record inserted correctly; false if not.
     */
    public static function insert(): bool
    {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields(["id", 'tax_id']);
            $id = $pdoDb->request("INSERT", "expense");

            $lineItemTaxId = isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "";
            self::expenseItemTax($id, $lineItemTaxId, $_POST['amount'], "1", "insert");
        } catch (PdoDbException $pde) {
            error_log("Expense::insert() - error: " . $pde->getMessage() . " _POST info - " . print_r($_POST, true));
            return false;
        }

        return true;
    }

    /**
     * Update an expense record.
     * @return bool true if update succeeded; false if not.
     */
    public static function update(): bool
    {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields(["id", "domain_id"]);
            $pdoDb->addSimpleWhere("domain_id", $_POST['domain_id'], "AND");
            $pdoDb->addSimpleWhere("id", $_GET['id']);
            if (!$pdoDb->request("UPDATE", "expense")) {
                return false;
            }

            $lineItemTaxId = isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "";
            self::expenseItemTax($_GET['id'], $lineItemTaxId, $_POST['amount'], "1", "update");
        } catch (PdoDbException $pde) {
            error_log("Expense::update() - id[{$_GET['id']}] error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Insert/update the multiple taxes per line item into the si_expense_item_tax table
     * @param int $expenseId
     * @param array $lineItemTaxId
     * @param float $unitPrice
     * @param float $quantity
     * @param string $action
     * @return bool true if processed without error; false otherwise.
     */
    public static function expenseItemTax(int $expenseId, array $lineItemTaxId, float $unitPrice, float $quantity, string $action = ""): bool
    {
        if (empty($lineItemTaxId)) {
            return false;
        }

        // if editing invoice delete all tax info then insert updated info.
        try {
            $requests = new Requests();
            if ($action == "update") {
                $request = new Request("DELETE", "expense_item_tax");
                $request->addSimpleWhere("expense_id", $expenseId);
            }

            foreach ($lineItemTaxId as $value) {
                if (!empty($value)) {
                    $tax = Taxes::getOne($value);

                    $taxAmount = Taxes::lineItemTaxCalc($tax, $unitPrice, $quantity);

                    $request = new Request("INSERT", "expense_item_tax");
                    $request->setExcludedFields("id");
                    $request->setFauxPost([
                        "expense_id" => $expenseId,
                        "tax_id" => $tax['tax_id'],
                        "tax_type" => $tax['type'],
                        "tax_rate" => $tax['tax_percentage'],
                        "tax_amount" => $taxAmount
                    ]);
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

}
