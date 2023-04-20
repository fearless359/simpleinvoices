<?php

namespace Inc\Claz;

use NumberFormatter;

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
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getTaxes();
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $LANG['taxRate'] . ' ' . $row['tax_description'];
            $editName = $LANG['edit'] . ' ' . $LANG['taxRate'] . ' ' . $row['tax_description'];

            $action =
                "<a class='index_table' title='$viewName' " .
                   "href='index.php?module=tax_rates&amp;view=view&amp;id={$row['tax_id']}'>" .
                    "<img src='images/view.png' class='action' alt='$viewName'/>" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='$editName' " .
                   "href='index.php?module=tax_rates&amp;view=edit&amp;id={$row['tax_id']}'>" .
                    "<img src='images/edit.png' class='action' alt='$editName'/>" .
                "</a>";

            $enabled = $row['tax_enabled'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $percentage = round($row['tax_percentage'], 2);
            if ($percentage == 0) {
                $rate = 0;
            } elseif ($row['type'] == '%') {
                $rate = $percentage . $row['type'];
            } else {
                $rate = Util::currency($percentage);
            }

            $tableRows[] = [
                'action' => $action,
                'taxDescription' => $row['tax_description'],
                'taxPercentage' => $rate,
                'enabled' => $enabledCol
            ];
        }

        return $tableRows;
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
        global $config, $LANG, $pdoDb;

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

            $symbol = NumberFormatter::create(
                $config['localLocale'],
                NumberFormatter::CURRENCY
            )->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

            foreach ($rows as $row) {
                if ($row['tax_percentage'] == 0) {
                    $row['type'] = '';
                } elseif ($row['type'] != '%') {
                    $row['type'] = $symbol;
                }
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
        global $config;

        $locale = str_replace('_', '-', $config['localLocale']);
        $symbol = NumberFormatter::create(
            $locale,
            NumberFormatter::CURRENCY
        )->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        return ['' => '', '%' => '%', $symbol => $symbol];
    }

    /**
     * Get a default tax record.
     * @return array Default tax record.
     */
    public static function getDefaultTax(): array
    {
        global $config, $pdoDb;

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

        if (empty($rows)) {
            return [];
        }

        $locale = str_replace('_', '-', $config['localLocale']);
        $symbol = NumberFormatter::create(
            $locale,
            NumberFormatter::CURRENCY
        )->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

        $taxes = $rows[0];
        $taxes['locale'] = $locale;
        if ($taxes['tax_percentage'] == 0) {
            $taxes['type'] = '';
        } elseif ($taxes['type'] != '%') {
            $taxes['type'] = $symbol;
        }

        return $taxes;
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
                'type' => empty($_POST['tax_percentage']) ? '' : $_POST['type'],
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
                'type'            => empty($_POST['tax_percentage']) ? '' : $_POST['type'],
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
     * @param float $quantity Number of units.
     * @param float $unitPrice Price of each unit.
     * @return float Total tax
     */
    public static function getTaxesPerLineItem(array $taxIds, float $quantity, float $unitPrice): float
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
     * @param float $quantity Number of units to tax.
     * @return float Total tax for the line item.
     */
    public static function lineItemTaxCalc(array $tax, float $unitPrice, float $quantity): float
    {
        // Calculate tax as a percentage of unit price or dollars per unit.
        if (isset($tax['type']) && $tax['type'] == "%") {
            return $tax['tax_percentage'] / 100 * $unitPrice * $quantity;
        }
        return $tax['tax_percentage'] * $quantity;
    }

}
