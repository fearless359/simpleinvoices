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
     * @param int $expense_id value to select records for.
     * @return array $rows for expense_item_tax with specified expense_id.
     */
    public static function getAll($expense_id)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setOrderBy("id");
            $pdoDb->addSimpleWhere("expense_id", $expense_id);
            $rows = $pdoDb->request("SELECT", "expense_item_tax");
        } catch (PdoDbException $pde) {
            error_log("ExpanseTax::get_all() - expense_id[$expense_id] error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Calculate and return the sum of tax_amount in all expense_item_tax
     * records selected by the expense_id.
     * @param int $expense_id value to select rows by.
     * @return int sum of tax_amount for specified $expense_id
     */
    public static function getSum($expense_id)
    {
        global $pdoDb;

        $sum = 0;
        try {
            $pdoDb->addToFunctions("SUM(tax_amount) AS sum");
            $pdoDb->addSimpleWhere("expense_id", $expense_id);
            $rows = $pdoDb->request("SELECT", "expense_item_tax");
            $sum = $rows[0]['sum'];
        } catch (PdoDbException $pde) {
            error_log("ExpenseTax::getSum() - expense_id[$expense_id] error: " . $pde->getMessage());
        }
        return $sum;
    }

    /**
     * Get expense_item_tax records for the specified $expense_id, grouped by the tax_id.
     * @param int $expense_id to select rows for.
     * @return array $rows selected for $expense_id.
     */
    public static function grouped($expense_id)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addToJoins(array("INNER", "expense", "e", "e.id", "et.expense_id"));

            $onClause = new OnClause();
            $onClause->addSimpleItem("t.tax_id", "et.tax_id", "AND");
            $onClause->addSimpleItem("t.domain_id", "e.domain_id");
            $pdoDb->addToJoins(array("INNER", "tax", "t", $onClause));

            $pdoDb->addSimpleWhere("e.id", $expense_id, "AND");
            $pdoDb->addSimpleWhere("e.domain_id", DomainId::get());

            $pdoDb->setGroupBy("t.tax_id");

            $pdoDb->addToFunctions("SUM(et.tax_amount) AS tax_amount");
            $pdoDb->addToFunctions("COUNT(*) AS count");

            $pdoDb->setSelectList("t.tax_description AS tax_name");

            $rows = $pdoDb->request("SELECT", "expense_item_tax", "et");
        } catch (PdoDbException $pde) {
            error_log("ExpenseTax::grouped() - expense_id[$expense_id] error: " . $pde->getMessage());
        }
        return $rows;
    }

}

