<?php
namespace Inc\Claz;

/**
 * Class PaymentType
 * @package Inc\Claz
 */
class PaymentType {

    /**
     * Retrieve a specific payment_type record.
     * @param int $pt_id ID of record to retrieve.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     */
    public static function getOne($pt_id) {
        return self::getPaymentTypes($pt_id);
    }

    /**
     * Get payment type records.
     * @param boolean $active Set to <b>true</b> if you only want active payment types.
     *        Set to <b>false</b> or don't specify if you want all payment types.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     */
    public static function getAll($active=false) {
        return self::getPaymentTypes(null, $active);
    }

    /**
     * Common data access method. Retrieve record(s) per specified parameters.
     * @param int $pt_id If not null, the id of the record to retrieve.
     * @param bool $enabled If true, only enabled records are retrieved.
     * @return array Qualified record(s) retrieved. Test for empty array when
     *          no qualified records found.
     */
    private static function getPaymentTypes($pt_id = null, $enabled = false) {
        global $LANG, $pdoDb;

        $payment_types = array();
        try {
            if (isset($pt_id)) {
                $pdoDb->addSimpleWhere('pt_id', $pt_id, 'AND');
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

            $rows = $pdoDb->request("SELECT", "payment_types");
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $LANG['payment_type'] . ' ' . $row['pt_description'];
                $row['ename'] = $LANG['edit'] . ' ' . $LANG['payment_type'] . ' ' . $row['pt_description'];
                $row['image'] = ($row['pt_enabled'] == ENABLED ? "images/common/tick.png" :
                                                                 "images/common/cross.png");
                $payment_types[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("PaymentType::getPaymentTypes - error: " . $pde->getMessage());
        }

        if (empty($payment_types)) {
            return array();
        }
        return (isset($pt_id) ? $payment_types[0] : $payment_types);
    }

    /**
     * Get ID for payment type and set the type up if it doesn't exist.
     * @param string $description This is the payment type.
     * @return string ID of payment type record.
     */
    public static function selectOrInsertWhere($description) {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("pt_description", $description, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "DISTINCT pt_id", "count"));

            $pdoDb->setSelectList("pt_id");
            $pdoDb->setGroupBy("pt_id");

            $rows = $pdoDb->request("SELECT", "payment_types");
            if (empty($rows)) {
                //add new payment type if no Paypal type
                self::insert($description, ENABLED);
                return self::selectOrInsertWhere($description);
            }
        } catch (PdoDbException $pde) {
            error_log("PaymentType::selectOrInsertWhere() - description[$description] - error: " . $pde->getMessage());
        }

        return (empty($rows) ? '' : $rows[0]['pt_id']);
    }

    /**
     * Get a default payment type.
     * @return string Default payment type.
     */
    public static function getDefaultPaymentType() {
        global $pdoDb;

        $rows = array();
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

        return (empty($rows) ? '' : $rows[0]['pt_description']);
    }

    /**
     * Insert a new record using the values in the class properties.
     * @return integer Unique <b>pt_id</b> value assigned to the new record.
     */
    public static function insert() {
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
     * @param string $pt_id Unique ID for this record.
     * @return boolean <b>true</b> if update was successful; otherwise <b>false</b>.
     */
    public static function update($pt_id) {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields(array("pt_id", "domain_id"));

            // Note that we don't need to include the domain_id in the key since this is a unique key.
            $pdoDb->addSimpleWhere("pt_id", $pt_id);

            return $pdoDb->request("UPDATE", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::update() - pt_id[$pt_id] error: " . $pde->getMessage());
        }
        return false;
    }
}
