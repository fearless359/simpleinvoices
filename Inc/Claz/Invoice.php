<?php

namespace Inc\Claz;

use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class Invoice
 * @package Inc\Claz
 */
class Invoice
{

    /**
     * Calculate the number of invoices in the database
     * @return integer Count of invoices in the database
     */
    public static function count()
    {
        global $pdoDb;

        DomainId::get();

        $rows = 0;
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "invoices");
        } catch (PdoDbException $pde) {
            error_log("Invoice::count() - Error: " . $pde->getMessage());
        }
        return count($rows);
    }

    /**
     * Retrieve an invoice.
     * @param integer $id
     * @return array $invoice
     */
    public static function getOne($id)
    {
        return self::getInvoices($id);
    }

    /**
     * Get all the invoice records with associated information.
     * <strong>NOTE:</strong> DO NOT CLEAR $pdoDb as some selection and other values might have been added
     * @param string $sort field to order by, defaults to index_name.
     * @param string $dir sort direction "asc" or "desc" for ascending or descending, defaults to "asc".
     * @return array invoice records.
     */
    public static function getAll($sort="index_name", $dir="asc")
    {
        return self::getInvoices(null, $sort, $dir);
    }

    /**
     * Retrieve all the invoices with using a having value if specified.
     * @param mixed $having Can be:
     *          <b>string:</b> One of the values defined in the buildHavings() method.
     *          <b>array:</b> Associative array with an array element:
     *              Ex: array("date_between" => array($start_date, $end_date)), or
     *                  array("date_between" => array($start_date, $end_date),
     *                        "real" => array());
     *          <b>array:</b> Sequential array containing one or more associative arrays:
     *              Ex: array(array("date_between" => array($start_date, $end_date),
     *                        array("real" => array());
     * @param string $sort field to order by (optional).
     * @param string $dir order by direction, "asc" or "desc" - (optional).
     * @param bool $manageTable true if select for manage.tpl table; false (default) if not.
     * @return array Invoices retrieved.
     */
    public static function getAllWithHavings($having, $sort="", $dir="", $manageTable = false)
    {
        global $pdoDb;

        if (!empty($having)) {
            try {
                if (is_array($having)) {
                    foreach ($having as $key => $value) {
                        if (is_int($key) && is_array($value)) {
                            foreach ($value as $key2 => $value2) {
                                if (empty($value2)) {
                                    $pdoDb->setHavings(Invoice::buildHavings($key2));
                                } else {
                                    $pdoDb->setHavings(Invoice::buildHavings($key2, $value2));
                                }
                            }
                        } else {
                            if (empty($value)) {
                                $pdoDb->setHavings(Invoice::buildHavings($key));
                            } else {
                                $pdoDb->setHavings(Invoice::buildHavings($key, $value));
                            }
                        }
                    }
                } else if (is_string($having)) {
                    $pdoDb->setHavings(Invoice::buildHavings($having));
                } else {
                    // Will be caught below
                    throw new PdoDbException("Invalid having parameter passed - ". print_r($having, true));
                }
            } catch (PdoDbException $pde) {
                error_log("Invoice::getAllWithHavings() - Error: " . $pde->getMessage());
            }
        }

        if ($manageTable) {
            return self::manageTableInfo();
        }

        return self::getInvoices(null, $sort, $dir);
    }

    /**
     * Retrieve all active invoices that have an amount owing.
     * @param int $customer_id Filters invoices selected if specified.
     * @return array Invoices with an ENABLED preferences status and
     *          a non-zero owing amount.
     */
    public static function getInvoicesOwing($customer_id = null)
    {
        global $pdoDb;

        $invoices_owing = array();
        try {
            $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
            $rows = Invoice::getAll("id", "desc");
            foreach ($rows as $row) {
                if ($row['status'] == ENABLED && $row['owing'] != 0) {
                    if (empty($customer_id) || $customer_id == $row['customer_id']) {
                        $invoices_owing[] = $row;
                    }
                }
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoicesOwing() - Error: " . $pde->getMessage());
        }
        return $invoices_owing;
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo()
    {
        global $auth_session, $config, $LANG;

        $read_only = ($auth_session->role_name == 'customer');

        $rows = self::getInvoices();
        $tableRows = array();
        foreach ($rows as $row) {
            // @formatter:off
            if ($read_only) {
                $inv_edit = '';
                $inv_pymt = '';
            } else {
                $inv_edit =   "<a class=\"index_table\" title=\"{$LANG['edit_view_tooltip']} {$row['index_id']}\" " .
                                   "href=\"index.php?module=invoices&amp;view=details&amp;id={$row['id']}&amp;action=view\">" .
                                    "<img src=\"images/common/edit.png\" class=\"action\" alt=\"edit\" />" .
                                "</a>";

                $inv_pymt =   "<!-- Alternatively: The Owing column can have the link on the amount instead of the payment icon code here -->";
                if ($row['status'] == ENABLED && $row['owing'] > 0) {
                    $inv_pymt .= "<!-- Real Invoice Has Owing - Process payment -->" .
                                 "<a title=\"{$LANG['process_payment']} {$row['index_id']}\" class=\"index_table\" " .
                                    "href=\"index.php?module=payments&amp;view=process&amp;id={$row['id']}&amp;op=pay_selected_invoice\">" .
                                     "<img src=\"images/common/money_dollar.png\" class=\"action\" alt=\"payment\"/>" .
                                 "</a>";
                } else if ($row['status'] == ENABLED) {
                    $inv_pymt .= "<!-- Real Invoice Payment Details if not Owing (get different color payment icon) -->" .
                                 "<a title=\"{$LANG['process_payment']} {$row['index_id']}\" class=\"index_table\" " .
                                    "href=\"index.php?module=payments&amp;view=details&amp;ac_inv_id={$row['id']}&amp;action=view\">" .
                                     "<img src=\"images/common/money_dollar.png\" class=\"action\" alt=\"payment\" />" .
                                 "</a>";
                } else {
                    $inv_pymt = "<!-- Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available -->" .
                                "<img src=\"images/common/money_dollar.png\" class=\"action\" alt=\"payment\" />";
                }
            }

            $action = "<a class='index_table' title=\"{$LANG['quick_view_tooltip']} {$row['index_id']}\" " .
                         "href=\"index.php?module=invoices&amp;view=quick_view&amp;id={$row['id']}\">" .
                          "<img src=\"images/common/view.png\" class=\"action\" alt=\"view\" />" .
                      "</a>" .
                      $inv_edit .
                      "<a class=\"index_table\" title=\"{$LANG['print_preview_tooltip']} {$row['index_id']}\" " .
                         "href=\"index.php?module=export&amp;view=invoice&amp;id={$row['id']}&amp;format=print\">" .
                          "<img src=\"images/common/printer.png\" class=\"action\" alt=\"print\" />" .
                      "</a>" .
                      "<a class=\"invoice_export_dialog\" id=\"btnShowSimple\" title=\"{$LANG['export_tooltip']} {$row['index_id']}\" " .
                         "href=\"#\" data-row-num=\"{$row['id']}\" data-spreadsheet=\"{$config->export->spreadsheet}\" " .
                         "data-wordprocessor=\"{$config->export->wordprocessor}\">" .
                          "<img src=\"images/common/page_white_acrobat.png\" class=\"action\" alt=\"spreadsheet\"/>" .
                      "</a>" .
                      $inv_pymt .
                      "<a title=\"{$LANG['email']} {$row['index_id']}\" class=\"index_table\" " .
                         "href=\"index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$row['id']}\">" .
                          "<img src=\"images/common/mail-message-new.png\" class=\"action\" alt=\"email\" />" .
                      "</a>";

            $tableRows[] = array(
                'action' => $action,
                'index_id' => $row['index_id'],
                'biller' => $row['biller'],
                'customer' => $row['customer'],
                'date' => $row['date'],
                'total' => $row['total'],
                'owing' => (isset($row['status']) ? $row['owing'] : ''),
                'aging' => (isset($row['aging']) ? $row['aging'] : ''),
                'currency_code' => $row['currency_code'],
                'locale' => preg_replace('/^(.*)_(.*)$/','$1-$2', $row['locale'])
            );
        }
        return $tableRows;
    }

    /**
     * Retrieve standard format invoice arrays.
     * <strong>NOTE:</strong> DO NOT CLEAR $pdoDb as some selection and other values might have been added
     * prior to calling this method.
     * @param int $id If not null, the ID of the invoices to retrieve.
     * @param string $sort field to order by, defaults to index_name.
     * @param string $dir sort direction "asc" or "desc" for ascending or descending, defaults to "asc".
     * @return array Selected rows.
     */
    private static function getInvoices($id = null, $sort = "", $dir = "")
    {
        global $auth_session, $pdoDb;

        $invoices = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('iv.id', $id, 'AND');
            }

            // If user role is customer or biller, then restrict invoices to those they have access to.
            if ($auth_session->role_name == 'customer') {
                $pdoDb->addSimpleWhere("c.id", $auth_session->user_id, "AND");
            } else if ($auth_session->role_name == 'biller') {
                $pdoDb->addSimpleWhere("b.id", $auth_session->user_id, "AND");
            }

            // If caller pass a null value, that mean there is no limit.
            $pdoDb->addSimpleWhere("iv.domain_id", DomainId::get());

            if (empty($sort) ||
                !in_array($sort, array('index_id', 'b.name', 'c.name', 'date', 'total', 'owing', 'aging'))) $sort = "index_id";
            if (empty($dir)) $dir = "DESC";
            $pdoDb->setOrderBy(array($sort, $dir));

            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.gross_total,0)", "gross"));

            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.total,0)", "total"));

            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.tax_amount,0)", "total_tax"));

            $jn = new Join("LEFT", "invoice_items", "ii");
            $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), 'AND');
            $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
            $pdoDb->addToJoins($jn);

            $fn = new FunctionStmt("SUM", "COALESCE(ac_amount,0)");
            $fr = new FromStmt("payment", "ap");
            $wh = new WhereClause();
            $wh->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), 'AND');
            $wh->addSimpleItem('ap.domain_id', new DbField('iv.domain_id'));
            $se = new Select($fn, $fr, $wh, null, "paid");
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt("DATE_FORMAT", "date, '%Y-%m-%d'", "date");
            $pdoDb->addToFunctions($fn);

            $fn = new FunctionStmt("CONCAT", "pf.pref_inv_wording, ' ', iv.index_id", 'index_name');
            $pdoDb->addToFunctions($fn);

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
                "iv.customer_id",
                "iv.biller_id",
                "iv.custom_field1",
                "iv.custom_field2",
                "iv.custom_field3",
                "iv.custom_field4",
                "iv.note",
                new DbField("iv.date", "date_original"),
                new DbField("iv.index_id", "index_id"),
                new DbField("iv.preference_id", "preference_id"),
                new DbField("iv.type_id", "type_id"),
                new DbField("b.name", "biller"),
                new DbField("c.name", "customer"),
                new DbField("pf.pref_description", "preference"),
                new DbField("pf.status", "status"),
                new DbField("pf.set_aging", "set_aging"),
                new DbField("pf.locale", "locale"),
                new DbField("pf.pref_currency_sign", "currency_sign"),
                new DbField("pf.currency_code", "currency_code"));
            $pdoDb->setSelectList($expr_list);

            $pdoDb->setGroupBy($expr_list);

            $rows = $pdoDb->request("SELECT", "invoices", "iv");
            foreach ($rows as $row) {
                $row['owing'] = $row['total'] - $row['paid'];
                $age_info = self::calculateAgeDays(
                    $row['id'],
                    $row['date'],
                    $row['owing'],
                    $row['last_activity_date'],
                    $row['aging_date'],
                    $row['set_aging']);

                // The merge will update fields that exist and append those that don't.
                self::updateAgingValues($row, $age_info);
                $row['tax_grouped']  = self::taxesGroupedForInvoice($row['id']);
                $row['calc_date']    = date('Y-m-d', strtotime($row['date']));
                $row['display_date'] = SiLocal::date($row['date']);
                $invoices[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoices() - Error: " . $pde->getMessage());
        }

        if (empty($invoices)) {
            return array();
        }

        return (isset($id) ? $invoices[0] : $invoices);
    }

    /**
     * Update values in invoice with just calculated values from calculateAgeDays().
     * Note: Previously used array_merge but it doesn't update numeric values which
     *       $ageInfo array contains.
     * @param array $invoice Reference to the array with invoice values.
     * @param array $ageInfo Updated aging information.
     */
    private static function updateAgingValues(&$invoice, $ageInfo)
    {
        if (isset($invoice['owing'])) {
            $invoice['owing'] = $ageInfo['owing'];
        }

        if (isset($invoice['last_activity_date'])) {
            $invoice['last_activity_date'] = $ageInfo['last_activity_date'];
        }

        if (isset($invoice['aging_date'])) {
            $invoice['aging_date'] = $ageInfo['aging_date'];
        }

        if (isset($invoice['age_days'])) {
            $invoice['age_days'] = $ageInfo['age_days'];
        }

        if (isset($invoice['aging'])) {
            $invoice['aging'] = $ageInfo['aging'];
        }
    }
    /**
     * Create the aging wording to show on the invoice list.
     * @param int $age_days to get string for.
     * @param float $owing Amount owing on invoice.
     * @return string Aging string (ex: 1-14, 15-30, etc).
     */
    private static function agingWording($age_days, $owing)
    {
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
     *          Note: This field should always be what is currently in the invoice record as it
     *          doesn't represent what is currently owning, but what was owing the last time the
     *          aging days was calculated.
     * @param string $last_activity_date yyyy-mm-dd date of last activity on this invoice.
     * @param string $aging_date yyyy-mm-dd date of last calculation of age_days.
     * @param bool $set_aging - If true, aging information will be calculated.
     * @return array age_info - associative array with updated key value pairs for
     *              "last_activity_date",
     *              "owing" ,
     *              "aging_date",
     *              "age_days"
     *              "aging" (aging is the wording such as 1-14).
     */
    private static function calculateAgeDays($id, $invoice_date, $owing, $last_activity_date, $aging_date, $set_aging)
    {
        // Don't recalculate $owing unless you have to because it involves DB reads.
        // Note that there is a time value in the dates so they are typically equal only when
        // an account is created.
        if ($set_aging && ($last_activity_date >= $aging_date || $owing > 0)) {
            $total = self::getInvoiceTotal($id);
            $paid = Payment::calcInvoicePaid($id);
            $owing = $total - $paid;
        }

        // We don't want create values here.
        if ($owing < 0 || !$set_aging) $owing = 0;
        $curr_dt_ymd_hms = '';
        try {
            $curr_dt = new DateTime();
            // We have the last activity date and the last aging date. If the activity
            // date is greater than the aging date, set the invoice aging value.
            $curr_dt_ymd_hms = $curr_dt->format('Y-m-d h:i:s');

            if ($set_aging && $owing > 0) {
                $inv_dt = new DateTime($invoice_date);
                $date_diff = $curr_dt->diff($inv_dt);
                $dys = $date_diff->days;
            } else {
                $dys = 0;
            }
        } catch (Exception $e) {
            $dys = 0;
        }

        // @formatter:off
        $age_info = array(
            "owing"              => $owing,
            "last_activity_date" => $last_activity_date,
            "aging_date"         => $curr_dt_ymd_hms,
            "age_days"           => $dys,
            "aging"              => self::agingWording($dys, $owing)
        );
        // @formatter:on

        return $age_info;
    }

    /**
     * Update aging information on all invoices that have had activity since the information was last set.
     * @param int $id if specified, the fields for a specified invoice will be updated. Otherwise, all
     *      invoices that need to be updated, will be updated.
     */
    public static function updateAging($id = null)
    {
        global $pdoDb;

        try {
            $pdoDb->setSelectList(array('id', 'date', 'owing', 'last_activity_date', 'aging_date', new DbField('pf.set_aging', 'set_aging')));

            $jn = new Join("LEFT", "preferences", "pf");
            $jn->addSimpleItem("pf.pref_id", new DbField("preference_id"));
            $pdoDb->addToJoins($jn);

            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id);
            } else {
                $pdoDb->addToWhere(new WhereItem(false, 'last_activity_date', '>=', new DbField('aging_date'), false, 'OR'));
                $pdoDb->addToWhere(new WhereItem(false, 'owing', '>', 0, false));
            }

            $rows = $pdoDb->request("SELECT", "invoices");

            $pdoDb->begin();
            foreach ($rows as $row) {
                // @formatter:off
                $id                 = $row['id'];
                $invoice_date       = $row['date'];
                $last_activity_date = $row['last_activity_date'];
                $owing              = $row['owing'];
                $aging_date         = $row['aging_date'];
                $set_aging          = $row['set_aging'];
                // @formatter:on
                $age_info = self::calculateAgeDays(
                    $id,
                    $invoice_date,
                    $owing,
                    $last_activity_date,
                    $aging_date,
                    $set_aging);

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
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateAging() - Error: " . $pde->getMessage());
        }
    }

    /**
     * Insert a new invoice record
     * @param array Associative array of items to insert into invoice record.
     * @return integer Unique ID of the new invoice record. 0 if insert failed.
     */
    public static function insert($list)
    {
        global $pdoDb;
        $lcl_list = $list;
        if (empty($lcl_list['domain_id'])) $lcl_list['domain_id'] = DomainId::get();

        $pref_group = Preferences::getOne($lcl_list['preference_id']);
        $lcl_list['index_id'] = Index::next('invoice', $pref_group['index_group']);

        try {
            $curr_date = new DateTime();
        } catch (Exception $e) {
            $curr_date = '';
        }
        $last_activity_date = $curr_date->format('Y-m-d h:i:s');

        $lcl_list['date'] = SiLocal::sqlDateWithTime($lcl_list['date']);
        $lcl_list['last_activity_date'] = $last_activity_date;
        $lcl_list['owing'] = 1; // force update of aging info
        $lcl_list['aging_date'] = $lcl_list['last_activity_date'];
        $lcl_list['age_days'] = 0;
        $lcl_list['aging'] = '';
        $pdoDb->setFauxPost($lcl_list);
        $id = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "invoices");
            Index::increment('invoice', $pref_group['index_group'], $lcl_list['domain_id']);
        } catch (PdoDbException $pde) {
            error_log("Invoice::insert() - Error: " . $pde->getMessage());
        }

        return $id;
    }

    /**
     * Insert a new invoice_item and the invoice_item_tax records.
     * @param array Associative array keyed by field name with its assigned value.
     * @param mixed $tax_ids
     * @return integer Unique ID of the new invoice_item record.
     */
    private static function insertItem($list, $tax_ids)
    {
        global $pdoDb;

        $lcl_list = $list;
        if (empty($lcl_list['domain_id'])) $lcl_list['domain_id'] = DomainId::get();

        $id = 0;
        try {
            $pdoDb->setFauxPost($list);
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "invoice_items");

            self::chgInvoiceItemTax($id, $tax_ids, $list['unit_price'], $list['quantity'], false);
        } catch (PdoDbException $pde) {
            error_log("Invoice::insertItem() - Error: " . $pde->getMessage());
        }
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
     */
    public static function insertInvoiceItem($invoice_id, $quantity, $product_id, $tax_ids,
                                             $description = "", $unit_price = "", $attribute = null)
    {
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

        $tax_amount = Taxes::getTaxesPerLineItem($tax_ids, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total = $gross_total + $tax_amount;

        // Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
        if ($description == $LANG['description']) $description = "";
        $list = array('invoice_id' => $invoice_id,
            'domain_id' => DomainId::get(),
            'quantity' => $quantity,
            'product_id' => $product_id,
            'unit_price' => $unit_price,
            'tax_amount' => $tax_amount,
            'gross_total' => $gross_total,
            'description' => $description,
            'total' => $total,
            'attribute' => json_encode($attr));
        $id = self::insertItem($list, $tax_ids);
        return $id;
    }

    /**
     *
     * @param integer $invoice_id
     * @return boolean <b>true</b> if update successful; otherwise <b>false</b>.
     */
    public static function updateInvoice($invoice_id)
    {
        global $pdoDb;

        $current_invoice = Invoice::getOne($_POST['id']);
        $current_pref_group = Preferences::getOne($current_invoice['preference_id']);

        $new_pref_group = Preferences::getOne($_POST['preference_id']);

        $index_id = $current_invoice['index_id'];

        if ($current_pref_group['index_group'] != $new_pref_group['index_group']) {
            $index_id = Index::increment('invoice', $new_pref_group['index_group']);
        }

        // Note that this will make the last activity date greater than the aging_date which will force the
        // aging information to be recalculated.
        try {
            $curr_DateTime = new DateTime();
        } catch (Exception $e) {
            $curr_DateTime = '';
        }
        $last_activity_date = $curr_DateTime->format('Y-m-d h:i:s');

        $result = false;
        try {
            $pdoDb->addSimpleWhere("id", $invoice_id);
            $pdoDb->setFauxPost(array('index_id' => $index_id,
                'biller_id' => $_POST['biller_id'],
                'customer_id' => $_POST['customer_id'],
                'preference_id' => $_POST['preference_id'],
                'date' => SiLocal::sqlDateWithTime($_POST['date']),
                'last_activity_date' => $last_activity_date,
                'owing' => '1', // force update of aging information
                'note' => (empty($_POST['note']) ? "" : trim($_POST['note'])),
                'custom_field1' => (isset($_POST['custom_field1']) ? $_POST['custom_field1'] : ''),
                'custom_field2' => (isset($_POST['custom_field2']) ? $_POST['custom_field2'] : ''),
                'custom_field3' => (isset($_POST['custom_field3']) ? $_POST['custom_field3'] : ''),
                'custom_field4' => (isset($_POST['custom_field4']) ? $_POST['custom_field4'] : ''),
                'sales_representative' => (isset($_POST['sales_representative']) ? $_POST['sales_representative'] : '')));
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $result = $pdoDb->request("UPDATE", "invoices");
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateInvoice() - Error: " . $pde->getMessage());
        }
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
     */
    public static function updateInvoiceItem($id, $quantity, $product_id,
                                             $tax_ids, $description, $unit_price, $attribute)
    {
        global $LANG, $pdoDb;

        $attr = array();
        if (is_array($attribute)) {
            foreach ($attribute as $k => $v) {
                if ($attribute[$v] !== '') {
                    $attr[$k] = $v;
                }
            }
        }
        $tax_amount = Taxes::getTaxesPerLineItem($tax_ids, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total = $gross_total + $tax_amount;
        if ($description == $LANG['description']) $description = "";

        try {
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
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateInvoiceItem() - Error: " . $pde->getMessage());
        }
    }

    /**
     * Attempts to delete rows from the database.
     * Currently allows for deletion of invoices, invoice_items, and products entries.
     * All other $module values will fail. $idField is also checked on a per-table
     * basis, i.e. invoice_items can be either "id" or "invoice_id" while products
     * can only be "id". Invalid $module or $idField values return false, as do
     * calls that would fail foreign key checks.
     * @param string $module Table a row
     * @param string $idField
     * @param integer $id
     * @return bool true if delete processed, false if not.
     */
    public static function delete($module, $idField, $id)
    {
        /**
         * @var PdoDb $pdoDb ;
         */
        global $pdoDb;

        $has_domain_id = false;

        $lcltable = strtolower($module);
        switch ($lcltable) {
            case 'invoice_item_tax':
                // Not required by any FK relationships
                if ($idField != 'invoice_item_id') {
                    return false; // Fail, invalid ID field
                }

                $s_idField = $idField;
                break;

            case 'invoice_items':
                // Not required by any FK relationships
                if ($idField != 'id' && $idField != 'invoice_id') {
                    return false; // Fail, invalid ID field
                }

                $s_idField = $idField;
                break;

            case 'products':
                if ($idField != 'id') {
                    return false;
                }

                $rows = array();
                try {
                    // Check for use of product
                    $pdoDb->addSimpleWhere('product_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('product_id');

                    $rows = $pdoDb->request('SELECT', 'invoice_items');
                } catch (PdoDbException $pde) {
                    error_log('Invoice::delete() - Failed products - error: ' . $pde->getMessage());
                }

                if (count($rows) > 0) {
                    return false; // product still in use
                }

                $has_domain_id = true;
                $s_idField = $idField;
                break;

            case 'invoices':
                // Check for existing payments and line items
                $rows = array();
                try {
                    $pdoDb->addSimpleWhere('invoice_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('invoice_id');
                    $rows = $pdoDb->request('SELECT', 'invoice_items');
                } catch (PdoDbException $pde) {
                    error_log('Invoice::delete() - Failed invoices(1) - error: ' . $pde->getMessage());
                }
                $count = count($rows);

                $rows = array();
                try {
                    $pdoDb->addSimpleWhere('ac_inv_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('ac_inv_id');
                    $rows = $pdoDb->request('SELECT', 'payment');
                } catch (PdoDbException $pde) {
                    error_log('Invoice::delete() - Failed invoices(1) - error: ' . $pde->getMessage());
                }

                $count += count($rows);

                // Fail if line items or payments still exist, or and invoice id field specified.
                if ($count > 0 || $idField != 'id') {
                    return false;
                }

                $has_domain_id = true;
                $s_idField = $idField;
                break;

            default:
                $s_idField = ''; // Fail, no checks for this table exist yet
                break;
        }

        if ($s_idField == '') {
            return false; // Fail, column whitelisting not performed
        }

        $result = false;
        try {
            if ($has_domain_id) {
                $pdoDb->addSimpleWhere('domain_id', DomainId::get(), 'AND');
            }

            $pdoDb->addSimpleWhere($s_idField, $id);
            $result = $pdoDb->request('DELETE', $module);
        } catch (PdoDbException $pde) {
            error_log('Invoice::delete() - Failed delete - error: ' . $pde->getMessage());
        }

        return $result;
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
    public static function chgInvoiceItemTax($invoice_item_id, $line_item_tax_ids, $unit_price, $quantity, $update)
    {
        try {
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
                        $tax        = Taxes::getOne($value);
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
     * Get the invoice record by the index_id and current domain_id.
     * @param $index_id
     * @return array empty if no such record otherwise invoices record.
     */
    public static function getInvoiceByIndexId($index_id)
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere('index_id', $index_id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
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
     * @param $q
     * @return mixed
     */
    public static function getInvoicesWithHtmlTotals($q)
    {
        $results = array();
        if (isset($q)) {
            $rows = self::getAll();
            foreach ($rows as $row) {
                $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
                $row['date'] = SiLocal::date($row['date']);
                $row['total'] = self::getInvoiceTotal($row['id']);
                $row['paid'] = Payment::calcInvoicePaid($row['id']);

                $age_info = self::calculateAgeDays(
                    $row['id'],
                    $row['date'],
                    $row['owing'],
                    $row['last_activity_date'],
                    $row['aging_date'],
                    $row['preference_id']);
                self::updateAgingValues($row, $age_info);

                $results[] = $row;

                if (strpos(strtolower($row['index_id']), strtolower($q)) !== false) {
                    // @formatter:off
                    $total = Util::htmlsafe(number_format($row['total'],2));
                    $paid  = Util::htmlsafe(number_format($row['paid'],2));
                    $owing = Util::htmlsafe(number_format($row['owing'],2));
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
    public static function buildHavings($option, $parms = null)
    {
        $havings = "";
        try {
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
                    $havings->addSimple("owing", "<", 0);
                    $havings->addSimple("status", "=", ENABLED);
                    break;
                case "draft":
                    $havings->addSimple("status", "<>", ENABLED);
                    break;
                case "real":
                    $havings->addSimple("status", "=", ENABLED);
                    break;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::buildHavings() - Error: " . $pde->getMessage());
        }
        return $havings;
    }

    /**
     * Get the invoice-items associated with a specific invoice.
     * @param $id
     * @return array
     */
    public static function getInvoiceItems($id)
    {
        global $pdoDb;

        $invoiceItems = array();
        try {
            $pdoDb->addSimpleWhere("invoice_id", $id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy("id");
            $rows = $pdoDb->request("SELECT", "invoice_items");

            foreach ($rows as $invoiceItem) {
                if (isset($invoiceItem['attribute'])) {
                    $invoiceItem['attribute_decode'] = json_decode($invoiceItem['attribute'], true);
                    foreach ($invoiceItem['attribute_decode'] as $key => $value) {
                        $product_attributes = ProductAttributes::getOne($key);
                        $invoiceItem['attribute_json'][$key]['name'] = $product_attributes['name'];
                        $invoiceItem['attribute_json'][$key]['type'] = $product_attributes['type'];
                        $invoiceItem['attribute_json'][$key]['visible'] = $product_attributes['visible'];
                        $invoiceItem['attribute_json'][$key]['value'] = ProductValues::getOne($key);
                    }
                }

                $pdoDb->addSimpleWhere("id", $invoiceItem['product_id'], 'AND');
                $pdoDb->addSimpleWhere('domain_id', DomainId::get());
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
     */
    public static function getInvoiceType($id)
    {
        global $pdoDb;

        $result = array();
        try {
            $pdoDb->addSimpleWhere("inv_ty_id", $id);
            $result = $pdoDb->request("SELECT", "invoice_type");
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceType() - id[$id] - error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Function getInvoiceGross
     * Used to get the gross total for a given Invoice number
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        gross totals from si_invoice_items will be summed.
     * @return float Gross total amount for the invoice.
     */
    private static function getInvoiceGross($invoice_id)
    {
        global $pdoDb;

        $gross_total = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "gross_total", "gross_total"));
            $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
            $rows = $pdoDb->request("SELECT", "invoice_items");
            if (!empty($rows)) {
                $gross_total = $rows[0]['gross_total'];
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceGross() - Error: " . $pde->getMessage());
        }
        return $gross_total;
    }

    /**
     * Function getInvoiceTotal
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        totals from si_invoice_items will be summed.
     * @return float
     */
    private static function getInvoiceTotal($invoice_id)
    {
        global $pdoDb;

        $total = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "total", "total"));
            $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
            $rows = $pdoDb->request("SELECT", "invoice_items");
            if (!empty($rows)) {
                $total = $rows[0]['total'];
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceTotal() - invoice_id[$invoice_id] - error: " . $pde->getMessage());
        }
        return $total;
    }

    /**
     * Purpose: to show a nice summary of total $ for tax for an invoice
     * @param integer $invoice_id
     * @return integer Count of records found.
     */
    public static function numberOfTaxesForInvoice($invoice_id)
    {
        global $pdoDb;

        $count = 0;
        try {
            $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id, 'AND');
            $pdoDb->addSimpleWhere('item.domain_id', DomainId::get());

            $pdoDb->addToFunctions(new FunctionStmt("DISTINCT", new DbField("tax.tax_id")));

            $jn = new Join("INNER", "invoice_item_tax", "item_tax");
            $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setGroupBy("tax.tax_id");

            $rows = $pdoDb->request("SELECT", "invoice_items", "item");
            $count = count($rows);
        } catch (PdoDbException $pde) {
            error_log("Invoice::numberOfTaxesForInvoice() - invoice_id[$invoice_id] - error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * Generates a nice summary of total $ for tax for an invoice
     * @param integer $invoice_id The <b>id</b> column for the invoice to get info for.
     * @return array Rows retrieve.
     */
    private static function taxesGroupedForInvoice($invoice_id)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "item_tax.tax_amount", "tax_amount"));
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "*", "count"));

            $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id, 'AND');
            $pdoDb->addSimpleWhere('item.domain_id', DomainId::get());

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
        } catch (PdoDbException $pde) {
            error_log("Invoice::taxesGroupedForInvoice() - invoice_id[$invoice_id] - error: " . $pde->getMessage());
        }

        return $rows;
    }

    /**
     * Function: taxesGroupedForInvoiceItem
     * Purpose: to show a nice summary of total $ for tax for an invoice item.
     * Used for invoice editing
     * @param integer Invoice item ID
     * @return array Items found
     */
    private static function taxesGroupedForInvoiceItem($invoice_item_id)
    {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->setSelectList(array("item_tax.id AS row_id",
                "tax.tax_description AS tax_name",
                "tax.tax_id AS tax_id"));

            $pdoDb->addSimpleWhere("item_tax.invoice_item_id", $invoice_item_id);

            $jn = new Join("LEFT", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setOrderBy("row_id");

            $rows = $pdoDb->request("SELECT", "invoice_item_tax", "item_tax");
        } catch (PdoDbException $pde) {
            error_log("Invoice::taxesGroupedForInvoiceItem() - invoice_item_id[$invoice_item_id] - error: " . $pde->getMessage());
        }
        return $rows;
    }

    /**
     * Retrieve maximum invoice number assigned.
     * @return integer Maximum invoice number assigned.
     */
    public static function maxIndexId()
    {
        global $pdoDb;

        $maxIndexId = 0;
        try {
            $pdoDb->addToFunctions(new FunctionStmt("MAX", "index_id", "maxIndexId"));

            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "invoices");
            $maxIndexId = $rows[0]['maxIndexId'];
        } catch (PdoDbException $pde) {
            error_log("Invoice::maxIndexId() - Error: " . $pde->getMessage());
        }
        return $maxIndexId;
    }

    /**
     * Process a recurring item
     * @param $invoice_id
     * @return int
     */
    public static function recur($invoice_id)
    {
        global $config;

        try {
            $timezone = $config->phpSettings->date->timezone;
            $tz = new DateTimeZone($timezone);
            $dtm = new DateTime('now', $tz);
            $dt_tm = $dtm->format("Y-m-d H:i:s");
        } catch (Exception $e) {
            $dt_tm = '';
        }

        $invoice = self::getOne($invoice_id);
        $invoice_items = self::getInvoiceItems($invoice_id);
        // @formatter:off
        $list = array('biller_id'     => $invoice['biller_id'],
                      'customer_id'   => $invoice['customer_id'],
                      'type_id'       => $invoice['type_id'],
                      'preference_id' => $invoice['preference_id'],
                      'domain_id'     => $invoice['domain_id'],
                      'date'          => $dt_tm,
                      'note'          => $invoice['note'],
                      'custom_field1' => $invoice['custom_field1'],
                      'custom_field2' => $invoice['custom_field2'],
                      'custom_field3' => $invoice['custom_field3'],
                      'custom_field4' => $invoice['custom_field4']);
        $new_id = self::insert($list);

        // insert each line item
        foreach ($invoice_items as $invoice_item) {
            $list = array('invoice_id' => $new_id,
                          'domain_id'  => $invoice_item['domain_id'],
                          'quantity'   => $invoice_item['quantity'],
                          'product_id' => $invoice_item['product_id'],
                          'unit_price' => $invoice_item['unit_price'],
                          'tax_amount' => $invoice_item['tax_amount'],
                          'gross_total'=> $invoice_item['gross_total'],
                          'description'=> $invoice_item['description'],
                          'total'      => $invoice_item['total'],
                          'attribute'  => $invoice_item['attribute']);
            self::insertItem($list, $invoice_item['tax_id']);
        }
        // @formatter:on

        return $new_id;
    }

}
