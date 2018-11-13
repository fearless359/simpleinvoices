<?php
require_once 'include/class/Index.php';
require_once 'include/class/ProductAttributes.php';
require_once 'include/class/Requests.php';

class Invoice {

    /**
     * Create the aging wording to show on the invoice list.
     * @param int $age_days to get string for.
     * @param float $owing Amount owing on invoice.
     * @return string Aging string (ex: 1-14, 15-30, etc).
     */
    private static function aging_wording($age_days, $owing) {
        $age_str = '';
        if ($owing > 0 && $age_days > 0) {
            if ($age_days <= 14) {
                $age_str = '1-14';
            } else if ($age_days <= 30) {
                $age_str = '15-30';
            } else if ($age_days <= 60) {
                $age_str = '31-60';
            } else if ($age_days <= 90) {
                $age_str = '61-90';
            } else {
                $age_str = '90+';
            }
        }
        return $age_str;
    }

    /**
     * Calculate the age_days for an invoice. The age_days will be zero if the invoice has no amount owing,
     * otherwise it will be the number of days between the invoice date and the current date.
     * @param int $id of invoice to calculate age_days for.
     * @param string $invoice_date yyyy-mm-dd date invoice created.
     * @param float $owing on this invoice. Note: Set positive to force aging info recalculation.
     * @param string $last_activity_date yyyy-mm-dd date of last activity on this invoice.
     * @param string $aging_date yyyy-mm-dd date of last calculation of age_days.
     * @param int $pref_id - Calculate for type_id of 1 (Invoice) only.
     * @return array age_info - associative array with updated key value pairs for
     *              "last_activity_date",
     *              "owing" ,
     *              "aging_date",
     *              "age_days"
     *              "aging" (aging is the wording such as 1-14).
     * @throws PdoDbException
     */
    private static function calculate_age_days($id, $invoice_date, $owing, $last_activity_date, $aging_date, $pref_id) {

        // Don't recalculate $owing unless you have to because it involves DB reads.
        // Note that there is a time value in the dates so they are typically equal only when
        // an account is created.
        if ($pref_id == 1 && ($last_activity_date >= $aging_date || $owing > 0)) {
            $total = self::getInvoiceTotal($id);
            $paid = Payment::calc_invoice_paid($id);
            $owing = $total - $paid;
        }

        // We don't want create values here.
        if ($owing < 0 || $pref_id != 1) $owing = 0;

        $curr_dt = new DateTime();
        // We have the last activity date and the last aging date. If the activity
        // date is greater than the aging date, set the invoice aging value.
        $curr_dt_ymd_hms = $curr_dt->format('Y-m-d h:i:s');

        if ($pref_id == 1) {
            $inv_dt = new DateTime($invoice_date);
            $date_diff = $curr_dt->diff($inv_dt);
            $dys = $date_diff->days;
        } else {
            $dys = 0;
        }

        $age_info = array(
            "owing" => $owing,
            "last_activity_date" => $last_activity_date,
            "aging_date" => $curr_dt_ymd_hms,
            "age_days" => $dys,
            "aging" => self::aging_wording($dys, $owing)
        );

        return $age_info;
    }

    /**
     * Update aging information on all invoices that have had activity since the information was last set.
     * @param int $id if specified, the fields for a specified invoice will be updated. Otherwise, all
     *      invoices that need to be updated, will be updated.
     * @throws PdoDbException If update error occurs
     */
    public static function updateAging($id = null) {
        global $pdoDb;

        $pdoDb->setSelectList(array('id', 'date', 'owing', 'last_activity_date', 'aging_date', 'preference_id'));
        if (isset($id)) {
            $pdoDb->addSimpleWhere('id', $id);
        } else {
            $pdoDb->addToWhere(new WhereItem(false, 'last_activity_date', '>=', new DbField('aging_date'), false, 'OR'));
            $pdoDb->addToWhere(new WhereItem(false, 'owing', '>', 0, false));
        }
        $rows = $pdoDb->request("SELECT", "invoices");

        $pdoDb->begin();
        foreach ($rows as $row) {
            $id = $row['id'];
            $invoice_date = $row['date'];
            $last_activity_date = $row['last_activity_date'];
            $owing = $row['owing'];
            $aging_date = $row['aging_date'];
            $pref_id = $row['preference_id'];
            $age_info = self::calculate_age_days(
                $id,
                $invoice_date,
                $owing,
                $last_activity_date,
                $aging_date,
                $pref_id);

            try {
                $pdoDb->setFauxPost(array(
                    'owing' => $age_info['owing'],
                    'last_activity_date' => $age_info['last_activity_date'],
                    'aging_date' => $age_info['aging_date'],
                    'age_days' => $age_info['age_days'],
                    'aging' => $age_info['aging']
                ));
                $pdoDb->addSimpleWhere('id', $id);
                if (!$pdoDb->request('UPDATE', 'invoices')) {
                    // Note that will be caught by following catch block and message added to its output.
                    throw new PdoDbException(("Unable to update invoice aging information for id[$id]."));
                }
            } catch (PdoDbException $pde) {
                $pdoDb->rollback();
                throw new PdoDbException(("Invoice::updateAging() - Update error. " . $pde->getMessage()));
            }
        }
        $pdoDb->commit();
    }

    /**
     * Insert a new invoice record
     * @param array Associative array of items to insert into invoice record.
     * @return integer Unique ID of the new invoice record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insert($list) {
        global $pdoDb;
        $lcl_list = $list;
        if (empty($lcl_list['domain_id'])) $lcl_list['domain_id'] = domain_id::get();

        $pref_group = Preferences::getPreference($lcl_list['preference_id'], $lcl_list['domain_id']);
        $lcl_list['index_id'] = Index::next('invoice', $pref_group['index_group'], $lcl_list['domain_id']);

        $curr_date = new DateTime();
        $last_activity_date = $curr_date->format('Y-m-d h:i:s');

        $lcl_list['date'] = sqlDateWithTime($lcl_list['date']);
        $lcl_list['last_activity_date'] = $last_activity_date;
        $lcl_list['owing'] = 1; // force update of aging info
        $lcl_list['aging_date'] = $lcl_list['last_activity_date'];
        $lcl_list['age_days'] = 0;
        $lcl_list['aging'] = '';
        $pdoDb->setFauxPost($lcl_list);
        $pdoDb->setExcludedFields("id");

        $id = $pdoDb->request("INSERT", "invoices");

        Index::increment('invoice', $pref_group['index_group'], $lcl_list['domain_id']);
        return $id;
    }

    /**
     * Insert a new invoice_item and the invoice_item_tax records.
     * @param array Associative array keyed by field name with its assigned value.
     * @param mixed $tax_ids
     * @return integer Unique ID of the new invoice_item record.
     * @throws PdoDbException
     */
    private static function insert_item($list, $tax_ids) {
        global $pdoDb;

        $lcl_list = $list;
        if (empty($lcl_list['domain_id'])) $lcl_list['domain_id'] = domain_id::get();

        if (!self::invoice_items_check_fk(null, $list['product_id'], $tax_ids, true)) {
            error_log("Invoice::insert_item - Failed foreign key check");
            error_log("                       list - " . print_r($list, true));
            error_log("                       tax_ids - " .print_r($tax_ids, true));
            return null;
        }

        $pdoDb->setFauxPost($list);
        $pdoDb->setExcludedFields("id");
        $id = $pdoDb->request("INSERT", "invoice_items");

        self::chgInvoiceItemTax($id, $tax_ids, $list['unit_price'], $list['quantity'], false);
        return $id;
    }

    /**
     * Insert a new <b>invoice_items</b> record.
     * @param integer $invoice_id <b>id</b>
     * @param integer $quantity
     * @param integer $product_id
     * @param mixed $tax_ids
     * @param string $description
     * @param string $unit_price
     * @param array $attribute
     * @return integer <b>id</b> of new <i>invoice_items</i> record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertInvoiceItem($invoice_id, $quantity, $product_id, $tax_ids,
                                             $description = "", $unit_price = "", $attribute = null) {
        global $LANG;

        // do taxes
        $attr = array();
        if (!empty($attribute)) {
            foreach ($attribute as $k => $v) {
                if ($attribute[$v] !== '') {
                    $attr[$k] = $v;
                }
            }
        }

        $tax_amount  = Taxes::getTaxesPerLineItem($tax_ids, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total       = $gross_total + $tax_amount;

        // Remove jquery auto-fill description - refer jquery.conf.js autofill section
        if ($description == $LANG['description']) $description = "";
        $list = array('invoice_id' => $invoice_id,
                      'domain_id'  => domain_id::get(),
                      'quantity'   => $quantity,
                      'product_id' => $product_id,
                      'unit_price' => $unit_price,
                      'tax_amount' => $tax_amount,
                      'gross_total'=> $gross_total,
                      'description'=> $description,
                      'total'      => $total,
                      'attribute'  => json_encode($attr));
        $id = self::insert_item($list, $tax_ids);
        return $id;
    }

    /**
     *
     * @param integer $invoice_id
     * @return boolean <b>true</b> if update successful; otherwise <b>false</b>.
     * @throws PdoDbException
     */
    public static function updateInvoice($invoice_id) {
        global $pdoDb;

        $current_invoice = Invoice::select($_POST['id']);
        $current_pref_group = Preferences::getPreference($current_invoice['preference_id']);

        $new_pref_group = Preferences::getPreference($_POST['preference_id']);

        $index_id = $current_invoice['index_id'];

        if ($current_pref_group['index_group'] != $new_pref_group['index_group']) {
            $index_id = Index::increment('invoice', $new_pref_group['index_group']);
        }

        $type = $current_invoice['type_id'];
        // TODO: Add foreign key logic to database definition
        if (!self::invoice_check_fk($_POST['biller_id'], $_POST['customer_id'], $type, $_POST['preference_id'])) return null;

        // Note that this will make the last activity date greater than the aging_date which will force the
        // aging information to be recalculated.
        $curr_datetime = new DateTime();
        $last_activity_date = $curr_datetime->format('Y-m-d h:i:s');
        $pdoDb->addSimpleWhere("id", $invoice_id);
        $pdoDb->setFauxPost(array('index_id'           => $index_id,
                                  'biller_id'          => $_POST['biller_id'],
                                  'customer_id'        => $_POST['customer_id'],
                                  'preference_id'      => $_POST['preference_id'],
                                  'date'               => sqlDateWithTime($_POST['date']),
                                  'note'               => trim($_POST['note']),
                                  'last_activity_date' => $last_activity_date,
                                  'owing'              => '1', // force update of aging information
                                  'custom_field1'      => (isset($_POST['custom_field1']) ? $_POST['custom_field1'] : ''),
                                  'custom_field2'      => (isset($_POST['custom_field2']) ? $_POST['custom_field2'] : ''),
                                  'custom_field3'      => (isset($_POST['custom_field3']) ? $_POST['custom_field3'] : ''),
                                  'custom_field4'      => (isset($_POST['custom_field4']) ? $_POST['custom_field4'] : ''),
                                  'sales_representative' => (isset($_POST['sales_representative']) ? $_POST['sales_representative'] : '')));
        $pdoDb->setExcludedFields(array("id", "domain_id"));
        $result = $pdoDb->request("UPDATE", "invoices");
        return $result;
    }

    /**
     * Update invoice_items table for a specific entry.
     * @param int $id Unique id for the record to be updated.
     * @param int $quantity Number of items
     * @param int $product_id Unique id of the si_products record for this item.
     * @param mixed $tax_ids Unique id for the taxes to apply to this line item.
     * @param string $description Extended description for this line item.
     * @param float $unit_price Price of each unit of this item.
     * @param string $attribute Attributes for invoice.
     * @throws PdoDbException
     */
    public static function updateInvoiceItem($id    , $quantity   , $product_id,
                                             $tax_ids, $description, $unit_price, $attribute) {
        global $LANG, $pdoDb;

        $attr = array();
        if (is_array($attribute)) {
            foreach ($attribute as $k => $v) {
                if ($attribute[$v] !== '') {
                    $attr[$k] = $v;
                }
            }
        }
        $tax_amount  = Taxes::getTaxesPerLineItem($tax_ids, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total       = $gross_total + $tax_amount;
        if ($description == $LANG['description']) $description = "";

        if (self::invoice_items_check_fk(null, $product_id, $tax_ids, true)) {
            // @formatter:off
            $pdoDb->addSimpleWhere("id", $id);
            $pdoDb->setFauxPost(array('quantity'    => $quantity,
                                      'product_id'  => $product_id,
                                      'unit_price'  => $unit_price,
                                      'tax_amount'  => $tax_amount,
                                      'gross_total' => $gross_total,
                                      'description' => $description,
                                      'total'       => $total,
                                      'attribute'   => json_encode($attr)));
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->request("UPDATE", "invoice_items");
            // @formatter:on

            self::chgInvoiceItemTax($id, $tax_ids, $unit_price, $quantity, true);
        }
    }

    /**
     * Insert/update the multiple taxes for a invoice line item.
     * @param int $invoice_item_id
     * @param int $line_item_tax_ids
     * @param string $unit_price
     * @param int $quantity
     * @param boolean $update
     * @return boolean <b>true</b> if successful, <b>false</b> if not.
     */
    public static function chgInvoiceItemTax($invoice_item_id, $line_item_tax_ids, $unit_price, $quantity, $update) {
        /*
         * @TODO: if editing invoice delete all tax info then insert first then do insert again.
         *  This can probably can be done without delete - someone to look into this if required
         */
        try {
            $domain_id = domain_id::get();
            $requests = new Requests();
            if ($update) {
                $request = new Request("DELETE", "invoice_item_tax");
                $request->addSimpleWhere("invoice_item_id", $invoice_item_id);
                $requests->add($request);
            }

            if (is_array($line_item_tax_ids)) {
                foreach ($line_item_tax_ids as $value) {
                    if (!empty($value)) {
                        // @formatter:off
                        $tax        = Taxes::getTaxRate($value, $domain_id);
                        $tax_amount = Taxes::lineItemTaxCalc($tax, $unit_price, $quantity);
                        $request = new Request("INSERT", "invoice_item_tax");
                        $request->setFauxPost(array('invoice_item_id' => $invoice_item_id,
                                                    'tax_id'          => $tax['tax_id'],
                                                    'tax_rate'        => $tax['tax_percentage'],
                                                    'tax_type'        => $tax['type'],
                                                    'tax_amount'      => $tax_amount));
                        // @formatter:on
                        $requests->add($request);
                    }
                }
            }
            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Invoice::invoice_item_tax(): Unable to process requests. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Select an invoice.
     * @param integer $id
     * @return array $invoice
     * @throws PdoDbException
     */
    public static function select($id) {
        global $pdoDb;

        // Make sure aging is current. Don't worry about performance as
        // this is only one record.
        self::updateAging($id);

        $domain_id = domain_id::get();
        // @formatter:off
        $list = array(new DbField("i.*"),
                      new DbField("i.date", "date_original"),
                      new DbField("i.owing", "owing"),
                      new DbField("p.pref_inv_wording", "preference"),
                      new DbField("p.status"));
        $pdoDb->setSelectList($list);
        $se = new Select(new FunctionStmt("CONCAT", "p.pref_inv_wording, ' ', i.index_id"), null, null, "index_name");
        $pdoDb->addToSelectStmts($se);

        $pdoDb->addToFunctions(new FunctionStmt("SUM", "ii.tax_amount", "total_tax"));

        $jn = new Join("LEFT", "preferences", "p");
        $jn->addSimpleItem("i.preference_id", new DbField("p.pref_id"), 'AND');
        $jn->addSimpleItem('i.domain_id', new DbField('p.domain_id'));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "invoice_items", "ii");
        $jn->addSimpleItem("ii.invoice_id", new DbField("i.id"), 'AND');
        $jn->addSimpleItem('ii.domain_id', new DbField('i.domain_id'));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("i.id", $id, "AND");
        $pdoDb->addSimpleWhere("i.domain_id", $domain_id);

        $rows = $pdoDb->request("SELECT", "invoices", "i");

        $invoice                  = $rows[0];
        $invoice['total']         = self::getInvoiceTotal($invoice['id']);
        $invoice['gross']         = self::getInvoiceGross($invoice['id']);
        $invoice['paid']          = Payment::calc_invoice_paid($invoice['id']);
        $invoice['invoice_items'] = self::getInvoiceItems($id);
        $invoice['tax_grouped']   = self::taxesGroupedForInvoice($id);
        $invoice['calc_date']     = date('Y-m-d', strtotime($invoice['date']));
        // @formatter:on

        return $invoice;
    }

    /**
     * Get all the invoice records with associated information.
     * @return array invoice records.
     */
    public static function get_all() {
        global $pdoDb;

        $results = array();
        try {
            $pdoDb->setSelectList("i.id as id");

            $fn = new FunctionStmt("CONCAT", "p.pref_inv_wording, ' ', i.index_id");
            $pdoDb->addToSelectStmts(new Select($fn, null, null, "index_name"));

            $jn = new Join("LEFT", "preferences", "p");
            $jn->addSimpleItem("i.preference_id", new DbField("p.pref_id"), "AND");
            $jn->addSimpleItem("i.domain_id", new DbField("p.domain_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->addSimpleWhere("i.domain_id", domain_id::get());

            $pdoDb->setOrderBy("index_name");

            $rows = $pdoDb->request("SELECT", "invoices", "i");
            foreach ($rows as $row) {
                $age_info = self::calculate_age_days(
                    $row['id'],
                    $row['date'],
                    $row['owing'],
                    $row['last_activity_date'],
                    $row['aging_date'],
                    $row['preference_id']);
                array_merge($row, $age_info);
                $results[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::get_all() - error: " . $pde->getMessage());
        }
        return $results;
    }

    /**
     * Calculate the number of invoices in the database
     * @return integer Count of invoices in the database
     * @throws PdoDbException
     */
    public static function count() {
        global $pdoDb;

        domain_id::get();

        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "invoices");
        return $rows[0]['count'];
    }

    /**
     * Get the invoice record by the index_id and current domain_id.
     * @param $index_id
     * @return array empty if no such record otherwise invoices record.
     */
    public static function getInvoiceByIndexId($index_id) {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere('index_id', $index_id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', domain_id::get());
            $rows = $pdoDb->request('SELECT', 'invoices');
        } catch (PdoDbException $pde) {
            error_log("Invoice::  getInvoiceByIndexId() - error: " . $pde->getMessage());
            return array();
        }
        if (empty($rows)) {
            return array();
        }
        return $rows[0];
    }

    /**
     * Get a specific invoice from the database.
     * @param $id
     * @return array
     */
    public static function getInvoice($id) {
        global $pdoDb;

        $domain_id = domain_id::get();

        $row = array();
        try {
            $pdoDb->setSelectAll(true);
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $rows = $pdoDb->request("SELECT", "invoices");
            if (empty($rows)) {
                return array();
            }

            $row = $rows[0];

            // @formatter:off
            $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
            $row['date']      = siLocal::date($row['date']);
            $row['total']     = self::getInvoiceTotal($row['id']);
            $row['gross']     = self::getInvoiceGross($row['id']);
            $row['paid']      = Payment::calc_invoice_paid($row['id']);

            $age_info = self::calculate_age_days(
                $row['id'],
                $row['date'],
                $row['owing'],
                $row['last_activity_date'],
                $row['aging_date'],
                $row['preference_id']);
            array_merge($row, $age_info);

            // invoice total tax
            $pdoDb->addToFunctions("SUM(tax_amount) AS total_tax");
            $pdoDb->addToFunctions("SUM(total) AS total");
            $pdoDb->addSimpleWhere("invoice_id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $rows = $pdoDb->request("SELECT", "invoice_items");

            $invoice_item_tax = $rows[0];
            $row['total_tax']   = $invoice_item_tax['total_tax'];
            $row['tax_grouped'] = self::taxesGroupedForInvoice($id);
            // @formatter:on
        } catch(PdoDbException $pde) {
            error_log("Invoice::getInvoice() - id[$id] error: " . $pde->getMessage());
        }
        return $row;
    }

    /**
     * @param $q
     * @return mixed
     * @throws PdoDbException
     */
    public static function getInvoices($q) {
        $results = array();
        if (isset($q)) {
            $rows = self::select_all('all');
            foreach ($rows as $row) {
                $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
                $row['date'] = siLocal::date($row['date']);
                $row['total'] = self::getInvoiceTotal($row['id']);
                $row['paid'] = Payment::calc_invoice_paid($row['id']);

                $results[] = $row;

                if (strpos(strtolower($row['index_id']), strtolower($q)) !== false) {
                    // @formatter:off
                    $total = htmlsafe(number_format($row['total'],2));
                    $paid  = htmlsafe(number_format($row['paid'],2));
                    $owing = htmlsafe(number_format($row['owing'],2));
                    echo "{$row['id']}|" .
                            "<table>" .
                                "<tr>" .
                                    "<td class='details_screen'>{$row['preference']}:</td>" .
                                    "<td>{$row['index_id']}</td>" .
                                    "<td class='details_screen'>Total: </td>" .
                                    "<td>{$total}</td>" .
                                "</tr>" .
                                "<tr>" .
                                    "<td class='details_screen'>Biller: </td>" .
                                    "<td>{$row['biller']}</td>" .
                                    "<td class='details_screen'>Paid: </td>" .
                                    "<td>{$paid}</td>" .
                                "</tr>" .
                                "<tr>" .
                                    "<td class='details_screen'>Customer: </td>" .
                                    "<td>{$row['customer']}</td>" .
                                    "<td class='details_screen'>Owing: </td>" .
                                    "<td><u>{$owing}</u></td>" .
                                "</tr>" .
                            "</table>\n";
                    // @formatter:on
                }
            }
        }

        return $results;
    }

    /**
     * Builds a <b>Havings</b> object for a predefined test.
     * Note: Valid parameters consist of an option and its parameter if a parameter is needed.
     * Here is the list of valid options:
     * <table>
     * <tr><th>Option</th><th>Parameter</th></tr>
     * <tr><td><b>date_between</b></td><td>array(start_date,end_date)</td></tr>
     * <tr><td><b>money_owed</b></td><td>n/a</td></tr>
     * <tr><td><b>paid</b></td><td>n/a</td></tr>
     * <tr><td><b>draft</b></td><td>n/a</td></tr>
     * <tr><td><b>real</b></td><td>n/a</td><tr>
     * </table>
     * @param string $option A valid option from the list above.
     * @param mixed $parms Parameter values required by the specified option.
     * @return mixed havings SQL statement
     */
    public static function buildHavings($option, $parms=null) {
        $havings = new Havings();
        switch ($option) {
            case "date_between":
                $havings->add(true, "date", "BETWEEN", $parms, true);
                break;
            case "money_owed":
                $havings->addSimple("owing", ">", 0);
                $havings->addSimple("status", "=", ENABLED);
                break;
            case "paid":
                $havings->addSimple("owing", "=", "", "OR");
                $havings->addSimple("owing", "<", 0 );
                $havings->addSimple("status", "=", ENABLED);
                break;
            case "draft":
                $havings->addSimple("status", "<>", ENABLED);
                break;
            case "real":
                $havings->addSimple("status", "=", ENABLED);
                break;
        }
        return $havings;
    }

    /**
     * Standard invoice selection for display in flexgrid by xml files.
     * <strong>NOTE:</strong> DO NOT CLEAR $pdoDb as some selection and other values might have been added
     * prior to calling this method.
     *
     * @param string $type Three setting:
     *        <ol>
     *          <li><b>count</b> - Get the count of all records.</li>
     *          <li><b>owing</b> - Get the total amount owing on all invoices.</li>
     *          <li><b>all</b> - Access all records. Same as 'count' except array of rows is returned.</li>
     *          <li><b>count_owing</b> - Return array containing the result of "count" and "owing".
     *              Indecies are "count" and "total_owing".</li>
     *          <li><b>&nbsp;&nbsp;</b> - All other settings are result in normal access of data based on specified criteria.</li>
     *        </ol>
     * @param string $sort Field to order results.
     * @param string $dir Direction of the order (ASC, DESC, A or D).
     * @param string $rp Number of lines to report per page.
     * @param string $page Page number processed.
     * @param string $qtype Special query field name.
     * @param string $query Value to look for. Will be enclosed in wildcards and searched using <i>LIKE</i>.
     * @return array Selected rows.
     * @throws PdoDbException
     */
    public static function select_all($type="", $sort="", $dir="", $rp=null, $page="", $qtype="", $query="") {
        global $auth_session, $pdoDb;

        // If user role is customer or biller, then restrict invoices to those they have access to.
        if ($auth_session->role_name == 'customer') {
            $pdoDb->addSimpleWhere("c.id", $auth_session->user_id, "AND");
        } elseif ($auth_session->role_name == 'biller') {
            $pdoDb->addSimpleWhere("b.id", $auth_session->user_id, "AND");
        }

        if (empty($sort) ||
            !in_array($sort, array('index_id', 'b.name', 'c.name', 'date', 'invoice_total', 'owing', 'aging'))) $sort = "index_id";
        if (empty($dir)) $dir = "DESC";
        $pdoDb->setOrderBy(array($sort, $dir));

        // If caller pass a null value, that mean there is no limit.
        $count = ($type == 'count' || $type == "count_owing");
        $calc_owing = ($type == 'owing' || $type == "count_owing");
        $all = ($type == 'all');
        if (isset($rp) && !$count && !$calc_owing && !$all) {
            if (empty($rp  )) $rp    = "25";
            if (empty($page)) $page  = "1";
            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);
        }

        if (!(empty($query) || empty($qtype))) {
            if (in_array($qtype, array('index_id','b.name','c.name','date','invoice_total','owing','aging'))) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("iv.domain_id", domain_id::get());

        $fn = new FunctionStmt("COALESCE", "SUM(ii.total),0");
        $fr = new FromStmt("invoice_items", "ii");
        $wh = new WhereClause();
        $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"), 'AND');
        $wh->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
        $se = new Select($fn, $fr, $wh, "invoice_total");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "SUM(ac_amount),0");
        $fr = new FromStmt("payment", "ap");
        $wh = new WhereClause();
        $wh->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), 'AND');
        $wh->addSimpleItem('ap.domain_id', new DbField('iv.domain_id'));
        $se = new Select($fn, $fr, $wh, "INV_PAID");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("DATE_FORMAT", "date, '%Y-%m-%d'", "date");
        $pdoDb->addToFunctions($fn);

        $fn = new FunctionStmt("CONCAT", "pf.pref_inv_wording, ' ', iv.index_id");
        $se = new Select($fn, null, null,"index_name");
        $pdoDb->addToSelectStmts($se);

        $jn = new Join("LEFT", "biller", "b");
        $jn->addSimpleItem("b.id", new DbField("iv.biller_id"), 'AND');
        $jn->addSimpleItem('b.domain_id', new DbField('iv.domain_id'));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "customers", "c");
        $jn->addSimpleItem("c.id", new DbField("iv.customer_id"), 'AND');
        $jn->addSimpleItem('c.domain_id', new DbField('iv.domain_id'));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "preferences", "pf");
        $jn->addSimpleItem("pf.pref_id", new DbField("iv.preference_id"), 'AND');
        $jn->addSimpleItem('pf.domain_id', new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $expr_list = array(
            "iv.id",
            "iv.domain_id",
            "iv.owing",
            "iv.last_activity_date",
            "iv.aging_date",
            "iv.age_days",
            "iv.aging",
            "iv.sales_representative",
            new DbField("iv.index_id", "index_id"),
            new DbField("iv.type_id", "type_id"),
            new DbField("b.name", "biller"),
            new DbField("c.name", "customer"),
            new DbField("iv.preference_id", "preference_id"),
            new DbField("pf.pref_description", "preference"),
            new DbField("pf.status", "status"));
        $pdoDb->setSelectList($expr_list);

        $pdoDb->setGroupBy($expr_list);

        $pdoDb->setGroupBy('date', 'index_name');

        $rows = $pdoDb->request("SELECT", "invoices", "iv");

        $results = array();
        $count = 0;
        $total_owing = 0;
        foreach($rows as $row) {
            $age_list = self::calculate_age_days(
                $row['id'],
                $row['date'],
                $row['owing'],
                $row['last_activity_date'],
                $row['aging_date'],
                $row['preference_id']);

            $count++;
            $total_owing += $age_list['owing'];

            // The merge will update fields that exist and append those that don't.
            $results[] = array_merge($row, $age_list);
        }

        // Return requested type.
        switch($type) {
            case 'count_owing':
                // Array with indecies for 'count' and 'total_owing'
                return array('count' => $count, 'total_owing' => $total_owing);

            case 'count' :
                // Count value.
                return $count;

            case 'calc_owing':
                // Total owing value.
                return $total_owing;

            case 'all':
                // All invoice rows, et al selected.
                break;

            default:
                // All invoice rows, et al selected.
                break;
        }

        // Return results for "all" or blank type.
        return $results;
    }

    /**
     * Get the invoice-items associated with a specific invoice.
     * @param $id
     * @return array
     */
    public static function getInvoiceItems($id) {
        global $pdoDb;

        $invoiceItems = array();
        try {
            $pdoDb->addSimpleWhere("invoice_id", $id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', domain_id::get());
            $pdoDb->setOrderBy("id");
            $rows = $pdoDb->request("SELECT", "invoice_items");

            foreach ($rows as $invoiceItem) {
                if (isset($invoiceItem['attribute'])) {
                    $invoiceItem['attribute_decode'] = json_decode($invoiceItem['attribute'], true);
                    foreach ($invoiceItem['attribute_decode'] as $key => $value) {
                        $invoiceItem['attribute_json'][$key]['name'] = ProductAttributes::getName($key);
                        $invoiceItem['attribute_json'][$key]['type'] = ProductAttributes::getType($key);
                        $invoiceItem['attribute_json'][$key]['visible'] = ProductAttributes::getVisible($key);
                        $invoiceItem['attribute_json'][$key]['value'] = ProductValues::getValue($key, $value);
                    }
                }

                $pdoDb->addSimpleWhere("id", $invoiceItem['product_id'], 'AND');
                $pdoDb->addSimpleWhere('domain_id', domain_id::get());
                $rows = $pdoDb->request("SELECT", "products");
                $invoiceItem['product'] = $rows[0];

                $tax = self::taxesGroupedForInvoiceItem($invoiceItem['id']);
                foreach ($tax as $key => $value) {
                    $invoiceItem['tax'][$key] = $value['tax_id'];
                }
                $invoiceItems[] = $invoiceItem;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceItems() - id[$id] error: " . $pde->getMessage());
        }
        return $invoiceItems;
    }

    /**
     * Get Invoice type.
     * @param string $id Invoice type ID.
     * @return array Associative array for <i>invoice_type</i> record accessed.
     * @throws PdoDbException
     */
    public static function getInvoiceType($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("inv_ty_id", $id);
        $result = $pdoDb->request("SELECT", "invoice_type");
        return $result;
    }

    /**
     * Function getInvoiceGross
     * Used to get the gross total for a given Invoice number
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        gross totals from si_invoice_items will be summed.
     * @return float Gross total amount for the invoice.
     * @throws PdoDbException
     */
    private static function getInvoiceGross($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "gross_total", "gross_total"));
        $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
        $rows = $pdoDb->request("SELECT", "invoice_items");
        return $rows[0]['gross_total'];
    }

    /**
     * Function getInvoiceTotal
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        totals from si_invoice_items will be summed.
     * @return float
     * @throws PdoDbException
     */
    private static function getInvoiceTotal($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "total", "total"));
        $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
        $rows = $pdoDb->request("SELECT", "invoice_items");
        return $rows[0]['total'];
    }

    /**
     * Purpose: to show a nice summary of total $ for tax for an invoice
     * @param integer $invoice_id
     * @return integer Count of records found.
     * @throws PdoDbException
     */
    public static function numberOfTaxesForInvoice($invoice_id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id, 'AND');
        $pdoDb->addSimpleWhere('item.domain_id', domain_id::get());

        $pdoDb->addToFunctions(new FunctionStmt("DISTINCT", new DbField("tax.tax_id")));

        $jn = new Join("INNER", "invoice_item_tax", "item_tax");
        $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setGroupBy("tax.tax_id");

        $rows = $pdoDb->request("SELECT", "invoice_items", "item");
        return count($rows);
    }

    /**
     * Generates a nice summary of total $ for tax for an invoice
     * @param integer $invoice_id The <b>id</b> column for the invoice to get info for.
     * @return array Rows retrieve.
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoice($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "item_tax.tax_amount", "tax_amount"));
        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "*", "count"));

        $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id, 'AND');
        $pdoDb->addSimpleWhere('item.domain_id', domain_id::get());

        $jn = new Join("INNER", "invoice_item_tax", "item_tax");
        $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);

        $expr_list = array(
            new DbField("tax.tax_id", "tax_id"),
            new DbField("tax.tax_description", "tax_name"),
            new DbField("item_tax.tax_rate", "tax_rate"));

        $pdoDb->setSelectList($expr_list);
        $pdoDb->setGroupBy($expr_list);

        $rows = $pdoDb->request("SELECT", "invoice_items", "item");

        return $rows;
    }

    /**
     * Function: taxesGroupedForInvoiceItem
     * Purpose: to show a nice summary of total $ for tax for an invoice item.
     * Used for invoice editing
     * @param integer Invoice item ID
     * @return array Items found
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoiceItem($invoice_item_id) {
        global $pdoDb;

        $pdoDb->setSelectList(array("item_tax.id AS row_id",
                                    "tax.tax_description AS tax_name",
                                    "tax.tax_id AS tax_id"));

        $pdoDb->addSimpleWhere("item_tax.invoice_item_id", $invoice_item_id);

        $jn = new Join("LEFT", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setOrderBy("row_id");

        $rows = $pdoDb->request("SELECT", "invoice_item_tax", "item_tax");
        return $rows;
    }

    /**
     * Retrieve maximum invoice number assigned.
     * @return integer Maximum invoice number assigned.
     * @throws PdoDbException
     */
    public static function maxIndexId() {
        global $pdoDb;

        $pdoDb->addToFunctions(new FunctionStmt("MAX", "index_id", "maxIndexId"));

        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $rows = $pdoDb->request("SELECT", "invoices");
        return $rows[0]['maxIndexId'];
    }

    /**
     * Process a recurring item
     * @param $invoice_id
     * @return int
     * @throws PdoDbException
     */
    public static function recur($invoice_id) {
        global $config;
        $timezone = $config->phpSettings->date->timezone;
        $tz = new DateTimeZone($timezone);
        $dtm = new DateTime('now', $tz);
        $dt_tm = $dtm->format("Y-m-d H:i:s");

        $invoice = self::select($invoice_id);
        // @formatter:off
        $list = array('biller_id'     => $invoice['biller_id'],
                      'customer_id'   => $invoice['customer_id'],
                      'type_id'       => $invoice['type_id'],
                      'preference_id' => $invoice['preference_id'],
                      'date'          => $dt_tm,
                      'note'          => $invoice['note'],
                      'custom_field1' => $invoice['custom_field1'],
                      'custom_field2' => $invoice['custom_field2'],
                      'custom_field3' => $invoice['custom_field3'],
                      'custom_field4' => $invoice['custom_field4']);
        $id = self::insert($list);

        // insert each line item
        foreach ($invoice['invoice_items'] as $v) {
            $list = array('invoice_id' => $id,
                          'quantity'   => $v['quantity'],
                          'product_id' => $v['product_id'],
                          'unit_price' => $v['unit_price'],
                          'tax_amount' => $v['tax_amount'],
                          'gross_total'=> $v['gross_total'],
                          'description'=> $v['description'],
                          'total'      => $v['total'],
                          'attribute'  => $v['attribute']);
            self::insert_item($list, $v['tax_id']);
        }
        // @formatter:on

        return $id;
    }

    /**
     * Manual verification of foreign keys.
     * Performs some manual FK checks on tables that the invoice table refers to.
     * Under normal conditions, this function will return true. Returning false
     * indicates that if the INSERT or UPDATE were to proceed, bad data could be
     * written to the database.
     * @param int $biller_id Unique ID for <b>si_biller</b> table.
     * @param int $customer_id Unique ID for <b>si_customers</b> table.
     * @param int $inv_ty_id Unique ID for <b>si_invoice_type</b> table.
     * @param int $pref_id Unique ID for <b>si_preferences</b> table.
     * @return boolean true if keys all test true; false otherwise.
     * @throws PdoDbException
     * TODO: Add FK logic to database.
     */
    private static function invoice_check_fk($biller_id, $customer_id, $inv_ty_id, $pref_id) {
        global $pdoDb;
        $domain_id = domain_id::get();

        // Check biller
        $pdoDb->addSimpleWhere("id", $biller_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "biller");
        if (empty($rows)) return false;

        // Check customer
        $pdoDb->addSimpleWhere("id", $customer_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "customers");
        if (empty($rows)) return false;

        // Check invoice type
        $pdoDb->addSimpleWhere("inv_ty_id", $inv_ty_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "invoice_type");
        if (empty($rows)) return false;

        // Check preferences
        $pdoDb->addSimpleWhere("pref_id", $pref_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "preferences");
        if (empty($rows)) return false;

        // All good
        return true;
    }

    /**
     * Manual verification of foreign keys.
     * Performs some manual FK checks on tables that the invoice table refers to.
     * Under normal conditions, this function will return true. Returning false
     * indicates that if the INSERT or UPDATE were to proceed, bad data could be
     * written to the database.
     * @param int $invoice_id Unique ID for <b>si_invoices</b> table.
     * @param int $product_id Unique ID for <b>si_products</b> table.
     * @param int $tax_ids Unique ID for <b>si_tax</b> table.
     * @param boolean $update <b>true</b> if check update constraints; <b>false</b> otherwise.
     * @return boolean true if keys all test true; false otherwise.
     * @throws PdoDbException
     * TODO: Add FK logic to database.
     */
    private static function invoice_items_check_fk($invoice_id, $product_id, $tax_ids, $update) {
        global $pdoDb_admin;
        $domain_id = domain_id::get();
        // Check invoice
        if (!$update || !empty($invoice_id)) {
            $pdoDb_admin->addSimpleWhere("id", $invoice_id, "AND");
            $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
            $pdoDb_admin->setSelectList("id");
            $rows = $pdoDb_admin->request("SELECT", "invoices");
            if (empty($rows)) return false;
        }

        // Check product
        $pdoDb_admin->addSimpleWhere("id", $product_id, "AND");
        $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
        $pdoDb_admin->setSelectList("id");
        $rows = $pdoDb_admin->request("SELECT", "products");
        if (empty($rows)) return false;

        // Check tax id
        if (( is_array($tax_ids) && !empty($tax_ids[0])) ||
            (!is_array($tax_ids) && !empty($tax_ids))) {
            if (!is_array($tax_ids)) {
                $tax_ids = array($tax_ids);
            }
            foreach ($tax_ids as $tax_id) {
                $pdoDb_admin->addSimpleWhere("tax_id", $tax_id, "AND");
                $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
                $pdoDb_admin->setSelectList("tax_id");
                $rows = $pdoDb_admin->request("SELECT", "tax");
                if (empty($rows)) return false;
            }
        }

        return true;
    }

}
