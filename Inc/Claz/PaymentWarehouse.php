<?php

namespace Inc\Claz;

use NumberFormatter;

/**
 * Class PaymentWarehouse
 * @package Inc\Claz
 */
class PaymentWarehouse
{
    /**
     * Get a record for the specified parameters.
     * @param int|string $id The id of the record to retrieve relative to the $idType.
     * @param int $idType indicates which key to use to retrieve a record.
     *          If 0, $id is the record id, if 1, $id is a customer_id,
     *          if 2, $id is the last_payment_id or if 3, the id is the payment_type.
     * @return array Selected record or empty array if no record found.
     */
    public static function getOne($id, int $idType): array
    {
        $rows = self::getPaymentWarehouseRecords($id, $idType);
        if (empty($rows)) {
            return [];
        }

        return $rows[0];
    }

    /**
     * Get all payment warehouse records.
     * @return array Empty if no records found.
     */
    public static function getAll(): array
    {
        $rows = self::getPaymentWarehouseRecords();
        if (empty($rows)) {
            return [];
        }

        return $rows;
    }

    /**
     * Get all payment warehouse records and format to use on manage template.
     * @return array
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getPaymentWarehouseRecords();

        $tableRows = [];
        foreach ($rows as $row) {
            $action =
                "<a class='index_table' title='{$LANG['view']} {$LANG['paymentWarehouseUc']}' " .
                   "href='index.php?module=payment_warehouse&amp;view=view&amp;id={$row['id']}'>" .
                    "<img src='images/view.png' alt='{$LANG['view']}' height='16' />" .
                "</a>" .
                "&nbsp;" .
                "<a class='index_table' title='{$LANG['edit']} {$LANG['paymentWarehouseUc']}' " .
                   "href='index.php?module=payment_warehouse&amp;view=edit&amp;id={$row['id']}' >" .
                    "<img src='images/edit.png' alt='{$LANG['edit']}'/>" .
                "</a>" .
                "&nbsp;" .
                "<a class='index_table' title='{$LANG['delete']} {$LANG['paymentWarehouseUc']}' " .
                   "href='index.php?module=payment_warehouse&amp;view=delete&amp;stage=1&amp;id={$row['id']}' >" .
                    "<img src='images/delete.png' alt='{$LANG['delete']}'/>" .
                "</a>";
            $row['action'] = $action;

            $pattern = '/^(.*)_(.*)$/';
            $tableRows[] = [
                'action' => $action,
                'cname' => $row['cname'],
                'last_payment_id' => $row['last_payment_id'],
                'balance' => $row['balance'],
                'description' => $row['description'],
                'check_number' => $row['check_number'],
                'currency_code' => $row['currency_code'],
                'locale' => preg_replace($pattern, '$1-$2', $row['locale'])
            ];
        }

        return $tableRows;
    }

    /**
     * Common data access method. Retrieve record(s) per specified parameters.
     * @param int|string|null $id The id of the record to retrieve relative to the $idType.
     * @param int|null $idType indicates which key to use to retrieve a record.
     *          If 0, $id is the record id, if 1, $id is a customer_id,
     *          if 2, $id is the last_payment_id or if 3, the id is the payment_type.
     * @return array Selected record or empty array if no record found.
     */
    private static function getPaymentWarehouseRecords($id = null, ?int $idType = null): array
    {
        global $pdoDb;

        $pwRecs = [];
        try {
            if (isset($id)) {
                switch ($idType) {
                    case 0:
                        $type = "pw.id";
                        break;

                    case 1:
                        $type = "pw.customer_id";
                        break;

                    case 2:
                        $type = "pw.last_payment_id";
                        break;

                    case 3:
                        $type = "pw.payment_type";
                        break;

                    default:
                        $str = "PaymentWarehouse->getOne() - Invalid idType[$idType]";
                        error_log($str);
                        throw new PdoDbException($str);
                }

                $pdoDb->addSimpleWhere($type, $id);
            }

            $pdoDb->setSelectList([
                'pw.*',
                "c.name AS cname",
                "pt.pt_description AS description",
                "pr.locale AS locale",
                "pr.pref_currency_sign AS currency_sign",
                "pr.currency_code AS currency_code"
            ]);

            $jn = new Join('LEFT', 'customers', 'c');
            $jn->addSimpleItem("pw.customer_id", new DbField("c.id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('LEFT', 'payment_types', 'pt');
            $jn->addSimpleItem('pt.pt_id', new DbField('pw.payment_type'));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "preferences", "pr");
            $jn->addSimpleItem("pr.pref_id", "1");
            $pdoDb->addToJoins($jn);

            $rows = $pdoDb->request("SELECT", "payment_warehouse", "pw");
            foreach ($rows as $row) {
                $locale = $row['locale'];
                $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
                $precision = $formatter->getAttribute(NumberFormatter::FRACTION_DIGITS);
                $row['precision'] = $precision;

                $pwRecs[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("PaymentWarehouse::getPaymentWarehouseRecords() - error: " . $pde->getMessage());
            return [];
        }

        if (empty($pwRecs)) {
            return [];
        }

        return $pwRecs;
    }

    /**
     * Insert a new record in the payment_warehouse table
     * @param int $customer_id ID of customer this record belongs to.
     * @param int|null $last_payment_id ID of payment this record associated with.
     * @param float $balance Warehoused balance for this record.
     * @param int $payment_type Payment type for this record.
     * @param string $check_number Check number for this record. If none, empty string.
     * @return bool true if successful insert, false if not.
     * @throws PdoDbException
     */
    public static function insert(int $customer_id, ?int $last_payment_id, float $balance, int $payment_type,
                                  string $check_number): bool
    {
        global $pdoDb;

        $pdoDb->setExcludedFields('id');

        $pdoDb->setFauxPost([
            'customer_id' => $customer_id,
            'last_payment_id' => $last_payment_id,
            'balance' => $balance,
            'payment_type' => $payment_type,
            'check_number' => $check_number
        ]);

        return $pdoDb->request('INSERT', 'payment_warehouse');
    }

    /**
     * Insert a new record in the payment_warehouse table
     * @param int $id ID of warehouse record being updated.
     * @param float $balance Warehoused balance for this record.
     * @param int $payment_type Payment type for this record.
     * @param string $check_number Check number for this record. If none, empty string.
     * @return bool true if successful insert, false if not.
     * @throws PdoDbException
     */
    public static function update(int $id, float $balance, int $payment_type, string $check_number): bool
    {
        global $pdoDb;

        $pdoDb->setExcludedFields([
            'id',
            'customer_id',
            'last_payment_id'
        ]);

        $pdoDb->setFauxPost([
            'balance' => $balance,
            'payment_type' => $payment_type,
            'check_number' => $check_number
        ]);

        $pdoDb->addSimpleWhere('id', $id);

        return $pdoDb->request('UPDATE', 'payment_warehouse');
    }

    /**
     * Delete the specified payment_warehouse record.
     * @param int $id ID of record to be deleted.
     * @return bool true if record deleted, false if deletion failed.
     * @throws PdoDbException
     */
    public static function delete(int $id): bool
    {
        global $pdoDb;

        $pdoDb->addSimpleWhere('id', $id);
        return $pdoDb->request("DELETE", 'payment_warehouse');
    }

    /**
     * Check for multiple payments for the invoice and return the second most recent ID.
     * @param int $acInvId Invoice ID to get payments for.
     * @return int|null The payment record id for the second most recent payment on the
     *          specified invoice ID. If less than two payments, the result is null.
     * @throws PdoDbException
     */
    public static function retrievePrevPaymentId(int $acInvId): ?int
    {
        global $pdoDb;
        
        $pdoDb->addSimpleWhere('ac_inv_id', $acInvId);
        $pdoDb->setOrderBy(['id', 'D']);
        $rows = $pdoDb->request("SELECT", "payment");
        if (count($rows) > 1) {
            return $rows[1]['id'];
        }
        return null;
    }

    /**
     * Adjust warehouse record for deletion of its most recent payment.
     * @param int $id Payment Warehouse record ID.
     * @param float $balance New value to set balance to.
     * @param int|null $prevPaymentId ID of payment prior to last one on the associated
     * @return bool true if
     */
    public static function updateBalance(int $id, float $balance, ?int $prevPaymentId = null): bool
    {
        global $pdoDb;

        $result = true;
        try {
            $pdoDb->setFauxPost([
                'balance' => $balance,
                'last_payment_id' => $prevPaymentId
            ]);
            $pdoDb->addSimpleWhere('id', $id);
            if (!$pdoDb->request("UPDATE", 'payment_warehouse')) {
                error_log("PaymentWarehouse::updateBalance() - Update request failed!");
                $result = false;
            }
        } catch(PdoDbException $pde) {
            error_log("PaymentWarehouse::updateBalance() - Update error: " . $pde->getMessage());
            $result = false;
        }
        return $result;
    }
}
