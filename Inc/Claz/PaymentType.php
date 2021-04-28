<?php

namespace Inc\Claz;

/**
 * Class PaymentType
 * @package Inc\Claz
 */
class PaymentType
{

    /**
     * Retrieve a specific payment_type record.
     * @param int $ptId ID of record to retrieve.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     */
    public static function getOne(int $ptId): array
    {
        return self::getPaymentTypes($ptId);
    }

    /**
     * Get payment type records.
     * @param bool $active Set to <b>true</b> if you only want active payment types.
     *        Set to <b>false</b> or don't specify if you want all payment types.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     */
    public static function getAll(bool $active = false): array
    {
        return self::getPaymentTypes(null, $active);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getAll(); // use getAll() as it sets desired parameters.
        $tableRows = [];
        foreach ($rows as $row) {
            $viewName = $LANG['view'] . ' ' . $LANG['paymentType'] . ' ' . $row['pt_description'];
            $editName = $LANG['edit'] . ' ' . $LANG['paymentType'] . ' ' . $row['pt_description'];

            $action =
                "<a class='index_table' title='$viewName' " .
                   "href='index.php?module=payment_types&amp;view=view&amp;id={$row['pt_id']}'>" .
                    "<img src='images/view.png' alt='$viewName' class='action'/>" .
                "</a>&nbsp;&nbsp;" .
                "<a class='index_table' title='$editName' " .
                   "href='index.php?module=payment_types&amp;view=edit&amp;id={$row['pt_id']}'>" .
                    "<img src='images/edit.png' alt='$editName' class='action'/>" .
                "</a>";

            $enabled = $row['pt_enabled'] == ENABLED;
            $image = $enabled ? "images/tick.png" : "images/cross.png";
            $enabledCol = "<span style='display: none'>{$row['enabled_text']}</span>" .
                "<img src='$image' alt='{$row['enabled_text']}' title='{$row['enabled_text']}' />";

            $tableRows[] = [
                'action' => $action,
                'ptDescription' => $row['pt_description'],
                'enabled' => $enabledCol
            ];
        }

        return $tableRows;
    }

    /**
     * Common data access method. Retrieve record(s) per specified parameters.
     * @param int|null $ptId If not null, the id of the record to retrieve.
     * @param bool $enabled If true, only enabled records are retrieved.
     * @return array Qualified record(s) retrieved. Test for empty array when
     *          no qualified records found.
     */
    private static function getPaymentTypes(?int $ptId = null, bool $enabled = false): array
    {
        global $LANG, $pdoDb;

        $paymentTypes = [];
        try {
            if (isset($ptId)) {
                $pdoDb->addSimpleWhere('pt_id', $ptId, 'AND');
            }

            if ($enabled) {
                $pdoDb->addSimpleWhere("pt_enabled", ENABLED, "AND");
            }

            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setOrderBy('pt_description');

            $oc = new CaseStmt("pt_enabled", "enabled_text");
            $oc->addWhen("=", ENABLED, $LANG['enabled']);
            $oc->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($oc);

            $pdoDb->setSelectAll(true);

            $paymentTypes = $pdoDb->request("SELECT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::getPaymentTypes - error: " . $pde->getMessage());
        }

        if (empty($paymentTypes)) {
            return [];
        }
        return isset($ptId) ? $paymentTypes[0] : $paymentTypes;
    }

    /**
     * Get ID for payment type and set the type up if it doesn't exist.
     * @param string $description This is the payment type.
     * @return string ID of payment type record.
     */
    public static function selectOrInsertWhere(string $description): string
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->addSimpleWhere("pt_description", $description, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "DISTINCT pt_id", "count"));

            $pdoDb->setSelectList("pt_id");
            $pdoDb->setGroupBy("pt_id");

            $rows = $pdoDb->request("SELECT", "payment_types");
            if (empty($rows)) {
                //add new payment type if no Paypal type
                self::insert();
                return self::selectOrInsertWhere($description);
            }
        } catch (PdoDbException $pde) {
            error_log("PaymentType::selectOrInsertWhere() - description[$description] - error: " . $pde->getMessage());
        }

        return empty($rows) ? '' : $rows[0]['pt_id'];
    }

    /**
     * Get a default payment type.
     * @return string Default payment type.
     */
    public static function getDefaultPaymentType(): string
    {
        global $pdoDb;

        $rows = [];
        try {
            $pdoDb->setSelectList(new DbField('p.pt_description', 'pt_description'));

            $pdoDb->addSimpleWhere('s.name', 'payment_type', 'AND');
            $pdoDb->addSimpleWhere('s.domain_id', DomainId::get());

            $jn = new Join("LEFT", 'payment_types', 'p');
            $jn->addSimpleItem('p.pt_id', new DbField('s.value'), 'AND');
            $jn->addSimpleItem('p.domain_id', new DbField('s.domain_id'));
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request('SELECT', 'system_defaults', 's');
        } catch (PdoDbException $pde) {
            error_log("PaymentType::getDefaultPaymentType() - Error: " . $pde->getMessage());
        }

        if (empty($rows)) {
            return '';
        }

        return empty($rows[0]['pt_description']) ? '' : $rows[0]['pt_description'];
    }

    /**
     * Insert a new record using the values in the class properties.
     * @return int Unique <b>pt_id</b> value assigned to the new record.
     */
    public static function insert(): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields("pt_id");
            $result = $pdoDb->request("INSERT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update an existing record using the values in the class properties.
     * @param int $ptId Unique ID for this record.
     * @return bool <b>true</b> if update was successful; otherwise <b>false</b>.
     */
    public static function update(int $ptId): bool
    {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->setExcludedFields(["pt_id", "domain_id"]);

            // Note that we don't need to include the domain_id in the key since this is a unique key.
            $pdoDb->addSimpleWhere("pt_id", $ptId);

            $result = $pdoDb->request("UPDATE", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::update() - pt_id[{$ptId}] error: " . $pde->getMessage());
        }
        return $result;
    }
}
