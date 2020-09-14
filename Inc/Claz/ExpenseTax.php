<?php

namespace Inc\Claz;

/**
 * Class ExpenseTax
 * @package Inc\Claz
 */
class ExpenseTax
{

    /**
     * Get expense_item_tax rows for a specified expense_id.
     * @param int $expenseId value to select records for.
     * @return array $rows for expense_item_tax with specified expense_id.
     */
    public static function getAll(int $expenseId): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->setOrderBy("id");
            $pdoDb->addSimpleWhere("expense_id", $expenseId);
            $rows = $pdoDb->request("SELECT", "expense_item_tax");
        } catch (PdoDbException $pde) {
            error_log("ExpanseTax::get_all() - expense_id[$expenseId] error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Calculate and return the sum of tax_amount in all expense_item_tax
     * records selected by the expense_id.
     * @param int $expenseId value to select rows by.
     * @return int sum of tax_amount for specified $expenseId
     */
    public static function getSum(int $expenseId): int
    {
        global $pdoDb;

        $sum = 0;
        try {
            $pdoDb->addToFunctions("SUM(tax_amount) AS sum");
            $pdoDb->addSimpleWhere("expense_id", $expenseId);
            
            $rows = $pdoDb->request("SELECT", "expense_item_tax");
            $sum = isset($rows[0]['sum']) ? $rows[0]['sum'] : 0;
        } catch (PdoDbException $pde) {
            error_log("ExpenseTax::getSum() - expense_id[$expenseId] error: " . $pde->getMessage());
        }
        return $sum;
    }

    /**
     * Get expense_item_tax records for the specified $expenseId, grouped by the tax_id.
     * @param int $expenseId to select rows for.
     * @return array $rows selected for $expenseId.
     */
    public static function grouped(int $expenseId): array
    {
        global $pdoDb;

        $rows = [];
        try {
            $on = new OnClause();
            $on->addSimpleItem("t.tax_id", new DbField("et.tax_id"), "AND");
            $on->addSimpleItem("t.domain_id", DomainId::get());
            $pdoDb->addToJoins(["INNER", "tax", "t", $on]);

            $pdoDb->addSimpleWhere("et.expense_id", $expenseId);

            $pdoDb->setGroupBy("et.tax_id");

            $pdoDb->addToFunctions("SUM(et.tax_amount) AS tax_amount");
            $pdoDb->addToFunctions("COUNT(*) AS count");

            $pdoDb->setSelectList("t.tax_description AS tax_name");

            $rows = $pdoDb->request("SELECT", "expense_item_tax", "et");
        } catch (PdoDbException $pde) {
            error_log("ExpenseTax::grouped() - expense_id[$expenseId] error: " . $pde->getMessage());
        }
        return $rows;
    }

}

