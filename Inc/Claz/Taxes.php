<?php

namespace Inc\Claz;

/**
 * Class Taxes
 * @package Inc\Claz
 */
class Taxes
{

    /**
     * Get a tax record.
     * @param int $id Unique ID record to retrieve.
     * @return array Row retrieved. Test for empty row for failure.
     */
    public static function getOne(int $id): array
    {
        return self::getTaxes($id);
    }

    /**
     * Get all tax table rows.
     * @return array Rows retrieved. Test for empty rows for failure.
     */
    public static function getAll(): array
    {
        return self::getTaxes();
    }

    /**
     * Get all active taxes records.
     * @return array Rows retrieved.
     */
    public static function getActiveTaxes(): array
    {
        return self::getTaxes(null, true);
    }

    /**
     * @param int|null $id If not null, id of record to retrieve
     * @param bool $activeOnly if true get enabled records only; false (default) for all records.
     * @return array rows retrieved.
     */
    private static function getTaxes(?int $id = null, bool $activeOnly = false): array
    {
        global $LANG, $pdoDb;

        $taxes = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('tax_id', $id, 'AND');
            }

            if ($activeOnly) {
                $pdoDb->addSimpleWhere('tax_enabled', ENABLED, 'AND');
            }

            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("tax_enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $pdoDb->setOrderBy("tax_description");

            $rows = $pdoDb->request("SELECT", "tax");
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $LANG['tax_rate'] . ' ' . $row['tax_description'];
                $row['ename'] = $LANG['edit'] . ' ' . $LANG['tax_rate'] . ' ' . $row['tax_description'];
                $row['image'] = $row['tax_enabled'] == ENABLED ? 'images/tick.png' : 'images/cross.png';
                $taxes[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Taxes::getAll() - error: " . $pde->getMessage());
        }
        if (empty($taxes)) {
            return [];
        }
        return isset($id) ? $taxes[0] : $taxes;
    }

    /**
     * Get tax types
     * @return array Types of tax records (% => percentage, $ => dollars)
     */
    public static function getTaxTypes(): array
    {
        return ['%' => '%', '$' => '$'];
    }

    /**
     * Get a default tax record.
     * @return array Default tax record.
     */
    public static function getDefaultTax(): array
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("s.name", "tax", "AND");
            $pdoDb->addSimpleWhere("s.domain_id", DomainId::get());

            $jn = new Join("LEFT", "tax", "t");
            $jn->addSimpleItem("t.tax_id", new DbField("s.value"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectAll(true);
            $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        } catch (PdoDbException $pde) {
            error_log("Taxes::getDefaultTax() - error: " . $pde->getMessage());
        }
        return empty($rows) ? [] : $rows[0];
    }

    /**
     * Check to see if a specified tax description already exists for the user's domain.
     * @param string $description of tax to check for.
     * @return bool true if record exists; false if not.
     */
    public static function verifyExists(string $description): bool
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere('tax_description', $description, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $rows = $pdoDb->request('SELECT', 'tax');
        } catch (PdoDbException $pde) {
            error_log("Tax::verifyExists(): description[$description] error - " . $pde->getMessage());
        }
        return !empty($rows);
    }
    /**
     * Insert a new tax rate.
     * @return int ID of new record. 0 if insert failed.
     */
    public static function insertTaxRate(): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setFauxPost(['domain_id' => DomainId::get(),
                'tax_description' => $_POST['tax_description'],
                'tax_percentage' => $_POST['tax_percentage'],
                'type' => $_POST['type'],
                'tax_enabled' => $_POST['tax_enabled']
            ]);
            $result = $pdoDb->request("INSERT", "tax");
        } catch (PdoDbException $pde) {
            error_log("Taxes::insertTaxRate() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update tax rate.
     * @return bool true if processed successfully, false if not.
     */
    public static function updateTaxRate(): bool
    {
        global $pdoDb;

        try {
            // @formatter:off
            $pdoDb->addSimpleWhere("tax_id", $_GET['id']);
            $pdoDb->setFauxPost([
                'tax_description' => $_POST['tax_description'],
                'tax_percentage'  => $_POST['tax_percentage'],
                'type'            => $_POST['type'],
                'tax_enabled'     => $_POST['tax_enabled']
            ]);
            // @formatter:on
            if ($pdoDb->request("UPDATE", "tax") === false) {
                return false;
            }
        } catch (PdoDbException $pde) {
            error_log("Taxes::updateTaxRate() - Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Calculate the total tax for the line item
     * @param array $taxIds Tax values to apply.
     * @param int $quantity Number of units.
     * @param float $unitPrice Price of each unit.
     * @return float Total tax
     */
    public static function getTaxesPerLineItem(array $taxIds, int $quantity, float $unitPrice): float
    {
        $taxTotal = 0;
        if (!empty($taxIds)) {
            foreach ($taxIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $tax = self::getOne(intval($id));
                $taxTotal += self::lineItemTaxCalc($tax, $unitPrice, $quantity);
            }
        }
        return $taxTotal;
    }

    /**
     * Calculate the total tax for this line item.
     * @param array $tax Taxes for the line item.
     * @param float $unitPrice Price for each unit.
     * @param int $quantity Number of units to tax.
     * @return float Total tax for the line item.
     */
    public static function lineItemTaxCalc(array $tax, float $unitPrice, int $quantity): float
    {
        // Calculate tax as a percentage of unit price or dollars per unit.
        if (isset($tax['type']) && $tax['type'] == "%") {
            return $tax['tax_percentage'] / 100 * $unitPrice * $quantity;
        }
        return $tax['tax_percentage'] * $quantity;
    }

}