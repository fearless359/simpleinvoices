<?php
namespace Inc\Claz;

/**
 * Class PaymentType
 * @package Inc\Claz
 */
class PaymentType {
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

            $expr_list = "pt_id";
            $pdoDb->setSelectList($expr_list);
            $pdoDb->setGroupBy($expr_list);

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
     * Get a specific payment type record.
     * @param int $id Unique ID of record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     *         Note that a field named, "enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function select($id) {
        global $LANG, $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("pt_id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("pt_enabled", "enabled");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::select() - id[$id] - error: " . $pde->getMessage());
        }
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Get payment type records.
     * @param boolean $active Set to <b>true</b> if you only want active payment types.
     *        Set to <b>false</b> or don't specify if you want all payment types.
     * @return array Rows retrieved. Note that an empty array is returned if no
     *         records are found.
     *         Note that a field named, "pt_enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function select_all($active=false) {
        global $LANG, $pdoDb;

        $rows = array();
        try {
            if ($active) {
                $pdoDb->addSimpleWhere("pt_enabled", ENABLED, "AND");
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $ca = new CaseStmt("pt_enabled", "pt_enabled");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setOrderBy("pt_description");

            $pdoDb->setSelectAll(true);
            $rows = $pdoDb->request("SELECT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::select_all() - active[$active] - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Get information for grid list.
     * @param string $type
     * @param string $dir
     * @param string $sort
     * @param int $rp
     * @param int $page
     * @return array|mixed
     */
    public static function xmlSql($type, $dir, $sort, $rp, $page) {
        global $pdoDb, $LANG;

        $rows = array();
        try {
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if (!(empty($qtype) || empty($query))) {
                if (in_array($qtype, array('pt_id', 'pt_description'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
                }
            }
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            if ($type == "count") {
                $pdoDb->addToFunctions("COUNT(*) AS count");
                $rows = $pdoDb->request("SELECT", "payment_types");
                return $rows[0]['count'];
            }

            // Check that the sort field is OK
            if (!preg_match('/^(asc|desc|A|D)$/iD', $dir)) $dir = 'A';
            if (!in_array($sort, array('pt_id', 'pt_description', 'enabled'))) {
                $sort = "pt_description";
                $dir = "A";
            }
            $pdoDb->setOrderBy(array($sort, $dir));

            if (intval($rp) != $rp) $rp = 25;
            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);

            $oc = new CaseStmt("pt_enabled", "enabled");
            $oc->addWhen("=", ENABLED, $LANG['enabled']);
            $oc->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($oc);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request("SELECT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("modules/payment_types/xml.php - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Insert a new record using the values in the class properties.
     * @param string $pt_description Payment type description.
     * @param string $pt_enabled Set to constant, <b><i>ENABLED</i></b> or <b><i>DISABLED</i></b>.
     * @return integer Unique <b>pt_id</b> value assigned to the new record.
     */
    public static function insert($pt_description, $pt_enabled) {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setExcludedFields("pt_id");
            $pdoDb->setFauxPost(array("pt_description" => $pt_description,
                "pt_enabled" => ($pt_enabled == ENABLED ? ENABLED : DISABLED),
                "domain_id" => DomainId::get()));
            $result = $pdoDb->request("INSERT", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::insert() - pt_description[$pt_description] " .
                "pt_enabled[$pt_enabled] - error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update an existing record using the values in the class properties.
     * @param string $pt_id Unique ID for this record.
     * @param string $pt_description Payment type description.
     * @param string $pt_enabled Set to constant, <b><i>ENABLED</i></b> or <b><i>DISABLED</i></b>.
     * @return boolean <b>true</b> if update was successful; otherwise <b>false</b>.
     */
    public static function update($pt_id, $pt_description, $pt_enabled) {
        global $pdoDb;

        try {
            $pdoDb->setExcludedFields(array("pt_id", "domain_id"));
            $pdoDb->setFauxPost(array("pt_description" => $pt_description,
                "pt_enabled" => ($pt_enabled == ENABLED ? ENABLED : DISABLED)));

            // Note that we don't need to include the domain_id in the key since this is a unique key.
            $pdoDb->addSimpleWhere("pt_id", $pt_id);

            return $pdoDb->request("UPDATE", "payment_types");
        } catch (PdoDbException $pde) {
            error_log("PaymentType::update() - pt_id[$pt_id] pt_description[$pt_description] " .
                "pt_enabled[$pt_enabled] error: " . $pde->getMessage());
        }
        return false;
    }
}
