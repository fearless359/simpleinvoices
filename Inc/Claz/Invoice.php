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
     * @return int Count of invoices in the database
     * @throws PdoDbException
     */
    public static function count(): int
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->setSelectList("id");
            $rows = $pdoDb->request("SELECT", "invoices");
        } catch (PdoDbException $pde) {
            error_log("Invoice::count() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return count($rows);
    }

    /**
     * Retrieve an invoice.
     * @param int $id
     * @return array $invoice
     * @throws PdoDbException
     */
    public static function getOne(int $id): array
    {
        return self::getInvoices($id);
    }

    /**
     * Get all the invoice records with associated information.
     * <strong>NOTE:</strong> DO NOT CLEAR $pdoDb as some selection and other values might have been added
     * @param string $sort field to order by, defaults to index_name.
     * @param string $dir sort direction "asc" or "desc" for ascending or descending, defaults to "asc".
     * @return array invoice records.
     * @throws PdoDbException
     */
    public static function getAll(string $sort="index_name", string $dir="desc"): array
    {
        return self::getInvoices(null, $sort, $dir);
    }

    /**
     * Retrieve all the invoices with using a having value if specified.
     * @param array|string $having Can be:
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
     * @throws PdoDbException
     */
    public static function getAllWithHavings($having, string $sort = "", string $dir = "", bool $manageTable = false): array
    {
        global $pdoDb;

        if (!empty($having)) {
            try {
                if (is_array($having)) {
                    foreach ($having as $key => $value) {
                        if (is_int($key) && is_array($value)) {
                            foreach ($value as $key2 => $value2) {
                                $pdoDb->setHavings(Invoice::buildHavings($key2, $value2));
                            }
                        } else {
                            $pdoDb->setHavings(Invoice::buildHavings($key, $value));
                        }
                    }
                } elseif (is_string($having)) {
                    $pdoDb->setHavings(Invoice::buildHavings($having));
                } else {
                    // Will be caught below
                    throw new PdoDbException("Invalid having parameter passed - ". print_r($having, true));
                }
            } catch (PdoDbException $pde) {
                error_log("Invoice::getAllWithHavings() - Error: " . $pde->getMessage());
                throw $pde;
            }
        }

        if ($manageTable) {
            return self::manageTableInfo();
        }

        return self::getInvoices(null, $sort, $dir);
    }

    /**
     * Retrieve all active invoices that have an amount owing.
     * @param int|null $customer_id Filters invoices selected if specified.
     * @return array Invoices with an ENABLED preferences status and
     *          a non-zero owing amount.
     * @throws PdoDbException
     */
    public static function getInvoicesOwing(?int $customer_id = null): array
    {
        global $pdoDb;

        try {
            $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
            $rows = Invoice::getAll("id");

            $invoiceOwing = [];
            foreach ($rows as $row) {
                if ($row['status'] == ENABLED && $row['owing'] != 0) {
                    if (!isset($customer_id) || $customer_id == $row['customer_id']) {
                        $invoiceOwing[] = $row;
                    }
                }
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoicesOwing() - Error: " . $pde->getMessage());
            throw $pde;
        }
        return $invoiceOwing;
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     * @throws PdoDbException
     */
    public static function manageTableInfo(): array
    {
        global $config, $LANG;

        session_name('SiAuth');
        session_start();
        $readOnly = $_SESSION['role_name'] == 'customer';
        $rows = self::getInvoices();
        $tableRows = [];
        foreach ($rows as $row) {
            // @formatter:off
            if ($readOnly) {
                $invEdit = '';
                $invPymt = '';
            } else {
                $invEdit = "<a class='index_table' title='{$LANG['edit']} {$row['index_id']}' " .
                              "href='index.php?module=invoices&amp;view=edit&amp;id={$row['id']}'>" .
                               "<img src='images/edit.png' class='action' alt='edit'>" .
                           "</a>";

                $invPymt = "<!-- Alternatively: The Owing column can have the link on the amount instead of the payment icon code here -->";
                if ($row['status'] == ENABLED && $row['owing'] > 0) {
                    $invPymt .= "<!-- Real Invoice Has Owing - Process payment -->" .
                                 "<a title='{$LANG['processPayment']} {$row['index_id']}' class='index_table' " .
                                    "href='index.php?module=payments&amp;view=process&amp;id={$row['id']}&amp;op=pay_selected_invoice'>" .
                                     "<img src='images/money_dollar.png' class='action' alt='payment'/>" .
                                 "</a>";
                } elseif ($row['status'] == ENABLED) {
                    $invPymt .= "<!-- Real Invoice Payment Details if not Owing (get different color payment icon) -->" .
                                 "<a title='{$LANG['processPayment']} {$row['index_id']}' class='index_table' " .
                                    "href='index.php?module=payments&amp;view=view&amp;ac_inv_id={$row['id']}'>" .
                                     "<img src='images/money_dollar.png' class='action' alt='payment' />" .
                                 "</a>";
                } else {
                    $invPymt .= "<!-- Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available -->" .
                                "<img src='images/money_dollar.png' class='action' alt='payment' />";
                }
            }

            $action = "<a class='index_table' title='{$LANG['quickViewTooltip']} {$row['index_id']}' " .
                         "href='index.php?module=invoices&amp;view=quickView&amp;id={$row['id']}'>" .
                          "<img src='images/view.png' class='action' alt='view' />" .
                      "</a>" .
                      $invEdit .
                      "<a class='index_table' title='{$LANG['printPreviewTooltip']} {$row['index_id']}' target='_blank' " .
                         "href='index.php?module=export&amp;view=invoice&amp;id={$row['id']}&amp;format=print'>" .
                          "<img src='images/printer.png' class='action' alt='print' />" .
                      "</a>" .
                      "<a class='invoice_export_dialog' id='btnShowSimple' title='{$LANG['exportUc']} {$row['index_id']}' " .
                         "href='#' data-row-num='{$row['id']}' data-spreadsheet='{$config['exportSpreadsheet']}' " .
                         "data-wordprocessor='{$config['exportWordProcessor']}'>" .
                          "<img src='images/page_white_acrobat.png' class='action' alt='spreadsheet'/>" .
                      "</a>" .
                      $invPymt .
                      "<a title='{$LANG['email']} {$row['index_id']}' class='index_table' " .
                         "href='index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$row['id']}'>" .
                          "<img src='images/mail-message-new.png' class='action' alt='email' />" .
                      "</a>";

            $pattern = '/^(.*)_(.*)$/';
            $tableRows[] = [
                'action' => $action,
                'index_id' => $row['index_id'],
                'biller' => $row['biller'],
                'customer' => $row['customer'],
                'preference' => $row['preference'],
                'date' => $row['date'],
                'total' => $row['total'],
                'owing' => isset($row['status']) ? $row['owing'] : '',
                'aging' => $row['aging'] ?? '',
                'currency_code' => $row['currency_code'],
                'locale' => preg_replace($pattern,'$1-$2', $row['locale'])
            ];
        }
        return $tableRows;
    }

    /**
     * Retrieve standard format invoice arrays.
     * <strong>NOTE:</strong> DO NOT CLEAR $pdoDb as some selection and other values might have been added
     * prior to calling this method.
     * @param int|null $id If not null, the ID of the invoices to retrieve.
     * @param string $sort field to order by, defaults to index_name.
     * @param string $dir sort direction "asc" or "desc" for ascending or descending, defaults to "asc".
     * @return array Selected rows.
     * @throws PdoDbException
     */
    private static function getInvoices(?int $id = null, string $sort = "", string $dir = ""): array
    {
        global $pdoDb;

        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('iv.id', $id, 'AND');
            }

            session_name('SiAuth');
            session_start();

            // If user role is customer or biller, then restrict invoices to those they have access to.
            if ($_SESSION['role_name'] == 'customer') {
                $pdoDb->addSimpleWhere("c.id", $_SESSION['user_id'], "AND");
            } elseif ($_SESSION['role_name'] == 'biller') {
                $pdoDb->addSimpleWhere("b.id", $_SESSION['user_id'], "AND");
            }

            // If caller pass a null value, that mean there is no limit.
            $pdoDb->addSimpleWhere("iv.domain_id", DomainId::get());

            if (empty($sort) || !in_array($sort, ['index_id', 'b.name', 'c.name', 'date', 'total', 'owing', 'aging'])) {
                $orderBy = new OrderBy("date", "D");
                $orderBy->addField("index_id", "D");
                $pdoDb->setOrderBy($orderBy);
            } else {
                if (empty($dir)) {
                    $dir = "DESC";
                }
                $pdoDb->setOrderBy([$sort, $dir]);
            }

            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.gross_total,0)", "gross"));
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.total,0)", "total"));
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.tax_amount,0)", "total_tax"));

            $jn = new Join("LEFT", "invoice_items", "ii");
            $jn->addSimpleItem("ii.invoice_id", new DbField("iv.id"), 'AND');
            $jn->addSimpleItem('ii.domain_id', new DbField('iv.domain_id'));
            $pdoDb->addToJoins($jn);

            $funcStmt = new FunctionStmt("SUM", "COALESCE(ac_amount,0)");

            $fromStmt = new FromStmt("payment", "ap");

            $whereClause = new WhereClause();
            $whereClause->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), 'AND');
            $whereClause->addSimpleItem('ap.domain_id', new DbField('iv.domain_id'));
            $selectStmt = new Select($funcStmt, $fromStmt, $whereClause, null, "paid");
            $pdoDb->addToSelectStmts($selectStmt);

            $funcStmt = new FunctionStmt("DATE_FORMAT", "date, '%Y-%m-%d'", "date");
            $pdoDb->addToFunctions($funcStmt);
            $funcStmt = new FunctionStmt("CONCAT", "pf.pref_inv_wording, ' ', iv.index_id", 'index_name');
            $pdoDb->addToFunctions($funcStmt);

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

            $exprList = [
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
                new DbField("pf.currency_code", "currency_code")
            ];
            $pdoDb->setSelectList($exprList);

            $pdoDb->setGroupBy($exprList);

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            $invoices = [];
            foreach ($rows as $row) {
                $owing = $row['total'] - $row['paid'];
                // Check to case where owing on invoice differs from calculated owing.
                // Also ignore contrived owing value of 1.
                if (Util::numberTrim($row['owing']) != 1 && $owing > 0 &&
                    Util::numberTrim($row['owing']) != Util::numberTrim($owing) && $row['preference_id'] == 1) {
                    error_log("Invoice::getInvoices() - Owing discrepancy on invoice id[{$row['id']}] - index_id[{$row['index_id']}]. " .
                        "Calculated owing[$owing] not equal to invoices table owning[{$row['owing']}]");
                }
                $ageInfo = self::calculateAgeDays(
                    $row['id'],
                    $row['date'],
                    $row['owing'],
                    $row['last_activity_date'],
                    $row['aging_date'],
                    $row['set_aging']);

                // The merge will update fields that exist and append those that don't.
                self::updateAgingValues($row, $ageInfo);
                $row['tax_grouped']  = self::taxesGroupedForInvoice($row['id']);
                $row['calc_date']    = date('Y-m-d', strtotime($row['date']));
                $row['display_date'] = Util::date($row['date']);
                $invoices[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoices() - Error: " . $pde->getMessage());
            throw $pde;
        }

        if (empty($invoices)) {
            return [];
        }

        return isset($id) ? $invoices[0] : $invoices;
    }

    /**
     * Update values in invoice with just calculated values from calculateAgeDays().
     * Note: Previously used array_merge but it doesn't update numeric values which
     *       $ageInfo array contains.
     * @param array $invoice Reference to the array with invoice values.
     * @param array $ageInfo Updated aging information.
     */
    private static function updateAgingValues(array &$invoice, array $ageInfo): void
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
    private static function agingWording(int $age_days, float $owing): string
    {
        $ageStr = '';
        if ($owing > 0 && $age_days > 0) {
            if ($age_days <= 14) {
                $ageStr = '1-14';
            } elseif ($age_days <= 30) {
                $ageStr = '15-30';
            } elseif ($age_days <= 60) {
                $ageStr = '31-60';
            } elseif ($age_days <= 90) {
                $ageStr = '61-90';
            } else {
                $ageStr = '90+';
            }
        }
        return $ageStr;
    }

    /**
     * Calculate the age_days for an invoice. The age_days will be zero if the invoice has no amount owing,
     * otherwise it will be the number of days between the invoice date and the current date.
     * @param int $id of invoice to calculate age_days for.
     * @param string $invoiceDate yyyy-mm-dd date invoice created.
     * @param float $owing on this invoice. Note: Set positive to force aging info recalculation.
     *          Note: This field should always be what is currently in the invoice record as it
     *          doesn't represent what is currently owning, but what was owing the last time the
     *          aging days was calculated.
     * @param string $lastActivityDate yyyy-mm-dd date of last activity on this invoice.
     * @param string $agingDate yyyy-mm-dd date of last calculation of age_days.
     * @param bool $setAging - If true, aging information will be calculated.
     * @return array age_info - associative array with updated key value pairs for
     *              "last_activity_date",
     *              "owing" ,
     *              "aging_date",
     *              "age_days"
     *              "aging" (aging is the wording such as 1-14).
     * @throws PdoDbException
     */
    private static function calculateAgeDays(int $id, string $invoiceDate, float $owing, string $lastActivityDate,
                                             string $agingDate, bool $setAging): array
    {

        // Don't recalculate $owing unless you have to because it involves DB reads.
        // Note that there is a time value in the dates so they are typically equal only when
        // an account is created.
        if ($setAging && ($lastActivityDate >= $agingDate || $owing > 0)) {
            $total = self::getInvoiceTotal($id);
            $paid = Payment::calcInvoicePaid($id);
            $owing = $total - $paid;
        }

        // We don't want create values here.
        if ($owing < 0 || !$setAging) {
            $owing = 0;
        }

        try {
            $currDt = new DateTime();
            // We have the last activity date and the last aging date. If the activity
            // date is greater than the aging date, set the invoice aging value.
            $currDtYmdHis = $currDt->format('Y-m-d h:i:s');

            if ($setAging && $owing > 0) {
                $invDt = new DateTime($invoiceDate);
                $dateDiff = $currDt->diff($invDt);
                $dys = $dateDiff->days;
            } else {
                $dys = 0;
            }
        } catch (Exception $exp) {
            error_log("Invoice::calculateAgeDays() - Unable to get current date. Error: {$exp->getMessage()}");
            throw new PdoDbException("Invoice::calculateAgeDays() - " . $exp->getMessage());
        }

        return [
            "owing"              => $owing,
            "last_activity_date" => $lastActivityDate,
            "aging_date"         => $currDtYmdHis,
            "age_days"           => $dys,
            "aging"              => self::agingWording($dys, $owing)
        ];
    }

    /**
     * Update aging information on all invoices that have had activity since the information was last set.
     * @param int|null $id if specified, the fields for a specified invoice will be updated. Otherwise, all
     *      invoices that need to be updated, will be updated.
     * @throws PdoDbException
     */
    public static function updateAging(?int $id = null): void
    {
        global $pdoDb;

        try {
            $pdoDb->setSelectList(['id', 'date', 'owing', 'last_activity_date', 'aging_date', new DbField('pf.set_aging', 'set_aging')]);

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
                $id               = $row['id'];
                $invoiceDate      = $row['date'];
                $lastActivityDate = $row['last_activity_date'];
                $owing            = $row['owing'];
                $agingDate        = $row['aging_date'];
                $setAging         = $row['set_aging'];
                // @formatter:on
                $ageInfo = self::calculateAgeDays(
                    $id,
                    $invoiceDate,
                    $owing,
                    $lastActivityDate,
                    $agingDate,
                    $setAging);

                try {
                    $pdoDb->setFauxPost([
                        'owing' => $ageInfo['owing'],
                        'last_activity_date' => $ageInfo['last_activity_date'],
                        'aging_date' => $ageInfo['aging_date'],
                        'age_days' => $ageInfo['age_days'],
                        'aging' => $ageInfo['aging']
                    ]);
                    $pdoDb->addSimpleWhere('id', $id);
                    if (!$pdoDb->request('UPDATE', 'invoices')) {
                        // Note that will be caught by following catch block and message added to its output.
                        $pdoDb->rollback();
                        throw new PdoDbException("Unable to update invoice aging information for id[$id].");
                    }
                } catch (PdoDbException $pde) {
                    $pdoDb->rollback();
                    // Caught below
                    throw $pde;
                }
            }
            $pdoDb->commit();
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateAging() - Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Insert a new invoice record
     * @param array Associative array of items to insert into invoice record.
     * @return int Unique ID of the new invoice record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insert(array $list): int
    {
        global $pdoDb;

        $lclList = $list;
        if (empty($lclList['domain_id'])) {
            $lclList['domain_id'] = DomainId::get();
        }

        $prefGroup = Preferences::getOne($lclList['preference_id']);
        $lclList['index_id'] = Index::next('invoice', $prefGroup['index_group']);

        try {
            $currDate = new DateTime();
        } catch (Exception $exp) {
            error_log("Invoice::insert() - Unable to set current date. Error - " . $exp->getMessage());
            throw new PdoDbException($exp->getMessage());
        }

        $lastActivityDate = $currDate->format('Y-m-d h:i:s');

        $lclList['date'] = Util::sqlDateWithTime($lclList['date']);
        $lclList['last_activity_date'] = $lastActivityDate;
        $lclList['owing'] = 1; // force update of aging info
        $lclList['aging_date'] = $lclList['last_activity_date'];
        $lclList['age_days'] = 0;
        $lclList['aging'] = '';
        $pdoDb->setFauxPost($lclList);

        try {
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "invoices");
            Index::increment('invoice', $prefGroup['index_group'], $lclList['domain_id']);
        } catch (PdoDbException $pde) {
            error_log("Invoice::insert() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $id;
    }

    /**
     * Insert a new invoice_item and the invoice_item_tax records.
     * @param array Associative array keyed by field name with its assigned value.
     * @param array|null $taxIds
     * @return int Unique ID of the new invoice_item record.
     * @throws PdoDbException
     */
    private static function insertItem(array $list, ?array $taxIds): int
    {
        global $pdoDb;

        $lclList = $list;
        if (empty($lclList['domain_id'])) {
            $lclList['domain_id'] = DomainId::get();
        }

        try {
            $pdoDb->setFauxPost($list);
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "invoice_items");

            self::chgInvoiceItemTax($id, $taxIds, $list['unit_price'], $list['quantity'], false);
        } catch (PdoDbException $pde) {
            error_log("Invoice::insertItem() - Error: " . $pde->getMessage());
            throw $pde;
        }
        return $id;
    }

    /**
     * Insert a new <b>invoice_items</b> record.
     * @param int $invoiceId <b>id</b>
     * @param float $quantity
     * @param int $productId
     * @param array $taxIds
     * @param string $description
     * @param float $unitPrice
     * @param array|null $attribute
     * @return int <b>id</b> of new <i>invoice_items</i> record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertInvoiceItem(int $invoiceId, float $quantity, int $productId, array $taxIds,
                                             string $description = "", float $unitPrice = 0, ?array $attribute = null): int
    {
        global $LANG;

        // do taxes
        $attr = [];
        if (!empty($attribute)) {
            foreach ($attribute as $key => $val) {
                if ($attribute[$val] !== '') {
                    $attr[$key] = $val;
                }
            }
        }

        $taxAmount = Taxes::getTaxesPerLineItem($taxIds, $quantity, $unitPrice);
        $grossTotal = $unitPrice * $quantity;
        $total = $grossTotal + $taxAmount;

        // Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
        if ($description == $LANG['descriptionUc']) {
            $description = "";
        }
        $list = ['invoice_id' => $invoiceId,
                 'domain_id' => DomainId::get(),
                 'quantity' => $quantity,
                 'product_id' => $productId,
                 'unit_price' => $unitPrice,
                 'tax_amount' => $taxAmount,
                 'gross_total' => $grossTotal,
                 'description' => $description,
                 'total' => $total,
                 'attribute' => json_encode($attr)
        ];
        return self::insertItem($list, $taxIds);
    }

    /**
     *
     * @param int $invoiceId
     * @return bool <b>true</b> if update successful; otherwise <b>false</b>.
     * @throws PdoDbException
     */
    public static function updateInvoice(int $invoiceId): bool
    {
        global $pdoDb;

        $currentInvoice = Invoice::getOne($_POST['id']);
        $currentPrefGroup = Preferences::getOne($currentInvoice['preference_id']);
        $newPrefGroup = Preferences::getOne($_POST['preference_id']);

        if ($currentPrefGroup['index_group'] != $newPrefGroup['index_group']) {
            $indexId = Index::increment('invoice', $newPrefGroup['index_group']);
        } else {
            $indexId = $currentInvoice['index_id'];
        }

        // Note that this will make the last activity date greater than the aging_date which will force the
        // aging information to be recalculated.
        try {
            $curDateTime = new DateTime();
        } catch (Exception $exp) {
            $str = "Invoice::updateInvoice() - Unable to access current DateTime - error: " . $exp->getMessage();
            error_log($str);
            throw new PdoDbException($str);
        }

        $lastActivityDate = $curDateTime->format('Y-m-d h:i:s');

        try {
            $pdoDb->addSimpleWhere("id", $invoiceId);
            $pdoDb->setFauxPost([
                'index_id' => $indexId,
                'biller_id' => $_POST['biller_id'],
                'customer_id' => $_POST['customer_id'],
                'preference_id' => $_POST['preference_id'],
                'date' => Util::sqlDateWithTime($_POST['date']),
                'last_activity_date' => $lastActivityDate,
                'owing' => '1', // force update of aging information
                'note' => empty($_POST['note']) ? "" : trim($_POST['note']),
                'custom_field1' => $_POST['custom_field1'] ?? '',
                'custom_field2' => $_POST['custom_field2'] ?? '',
                'custom_field3' => $_POST['custom_field3'] ?? '',
                'custom_field4' => $_POST['custom_field4'] ?? '',
                'sales_representative' => $_POST['sales_representative'] ?? ''
            ]);
            $pdoDb->setExcludedFields(["id", "domain_id"]);
            $result = $pdoDb->request("UPDATE", "invoices");
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateInvoice() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $result;
    }

    /**
     * Update invoice_items table for a specific entry.
     * @param int $id Unique id for the record to be updated.
     * @param float $quantity Number of items
     * @param int $productId Unique id of the si_products record for this item.
     * @param array  $taxIds Unique id for the taxes to apply to this line item.
     * @param string $description Extended description for this line item.
     * @param float $unitPrice Price of each unit of this item.
     * @param array|null $attribute Attributes for invoice.
     * @throws PdoDbException
     */
    public static function updateInvoiceItem(int $id, float $quantity, int $productId, array $taxIds,
                                             string $description, float $unitPrice, ?array $attribute = null): void
    {
        global $LANG, $pdoDb;

        $attr = [];
        if (is_array($attribute)) {
            foreach ($attribute as $key => $val) {
                if (!empty($val)) {
                    $attr[$key] = $val;
                }
            }
        }

        $attrJsonEncode = json_encode($attr);
        $taxAmount = Taxes::getTaxesPerLineItem($taxIds, $quantity, $unitPrice);
        $grossTotal = $unitPrice * $quantity;
        $total = $grossTotal + $taxAmount;
        if ($description == $LANG['descriptionUc']) {
            $description = "";
        }

        try {
            // @formatter:off
            $pdoDb->addSimpleWhere("id", $id);
            $pdoDb->setFauxPost([
                'quantity'    => $quantity,
                'product_id'  => $productId,
                'unit_price'  => $unitPrice,
                'tax_amount'  => $taxAmount,
                'gross_total' => $grossTotal,
                'description' => $description,
                'total'       => $total,
                'attribute'   => $attrJsonEncode
            ]);
            $pdoDb->setExcludedFields(["id", "domain_id"]);
            $pdoDb->request("UPDATE", "invoice_items");
            // @formatter:on

            self::chgInvoiceItemTax($id, $taxIds, $unitPrice, $quantity, true);
        } catch (PdoDbException $pde) {
            error_log("Invoice::updateInvoiceItem() - Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Attempts to delete rows from the database.
     * Currently, allows for deletion of invoices, invoice_items, and products entries.
     * All other $module values will fail. $idField is also checked on a per-table
     * basis, i.e. invoice_items can be either "id" or "invoice_id" while products
     * can only be "id". Invalid $module or $idField values return false, just as calls
     * that would fail foreign key checks.
     * @param string $module Table a row
     * @param string $idField
     * @param int $id
     * @return bool true if delete processed, false if not.
     * @throws PdoDbException
     */
    public static function delete(string $module, string $idField, int $id): bool
    {
        global $pdoDb;

        $hasDomainId = false;

        $lcltable = strtolower($module);
        switch ($lcltable) {
            case 'invoice_item_tax':
                // Not required by any FK relationships
                if ($idField != 'invoice_item_id') {
                    return false; // Fail, invalid ID field
                }

                $sIdField = $idField;
                break;

            case 'invoice_items':
                // Not required by any FK relationships
                if ($idField != 'id' && $idField != 'invoice_id') {
                    return false; // Fail, invalid ID field
                }

                $sIdField = $idField;
                break;

            case 'products':
                if ($idField != 'id') {
                    return false;
                }

                try {
                    // Check for use of product
                    $pdoDb->addSimpleWhere('product_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('product_id');

                    $rows = $pdoDb->request('SELECT', 'invoice_items');
                } catch (PdoDbException $pde) {
                    error_log('Invoice::delete() - Failed products - error: ' . $pde->getMessage());
                    throw $pde;
                }

                if (count($rows) > 0) {
                    return false; // product still in use
                }

                $hasDomainId = true;
                $sIdField = $idField;
                break;

            case 'invoices':
                // Check for existing payments and line items
                try {
                    $pdoDb->addSimpleWhere('invoice_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('invoice_id');
                    $rows = $pdoDb->request('SELECT', 'invoice_items');
                    $count = count($rows);

                    $pdoDb->addSimpleWhere('ac_inv_id', $id, 'AND');
                    $pdoDb->addSimpleWhere('domain_id', DomainId::get());

                    $pdoDb->setSelectList('ac_inv_id');
                    $rows = $pdoDb->request('SELECT', 'payment');
                } catch (PdoDbException $pde) {
                    error_log('Invoice::delete() - Failed invoices(1) - error: ' . $pde->getMessage());
                    throw $pde;
                }

                $count += count($rows);

                // Fail if line items or payments still exist, or and invoice id field specified.
                if ($count > 0 || $idField != 'id') {
                    return false;
                }

                $hasDomainId = true;
                $sIdField = $idField;
                break;

            default:
                $sIdField = ''; // Fail, no checks for this table exist yet
                break;
        }

        if ($sIdField == '') {
            return false; // Fail, column whitelisting not performed
        }

        try {
            if ($hasDomainId) {
                $pdoDb->addSimpleWhere('domain_id', DomainId::get(), 'AND');
            }

            $pdoDb->addSimpleWhere($sIdField, $id);
            $result = $pdoDb->request('DELETE', $module);
        } catch (PdoDbException $pde) {
            error_log('Invoice::delete() - Failed delete - error: ' . $pde->getMessage());
            throw $pde;
        }

        return $result;
    }

    /**
     * Insert/update the multiple taxes for a invoice line item.
     * @param int $invoiceItemId
     * @param array|null $lineItemTaxIds
     * @param float $unitPrice
     * @param float $quantity
     * @param bool $update
     * @throws PdoDbException
     */
    public static function chgInvoiceItemTax(int $invoiceItemId, ?array $lineItemTaxIds, float $unitPrice,
                                             float $quantity, bool $update): void
    {
        global $config;

        try {
            $requests = new Requests($config);
            if ($update) {
                $request = new Request("DELETE", "invoice_item_tax");
                $request->addSimpleWhere("invoice_item_id", $invoiceItemId);
                $requests->add($request);
            }

            if (!empty($lineItemTaxIds)) {
                foreach ($lineItemTaxIds as $value) {
                    if (!empty($value)) {
                        // @formatter:off
                        $tax = Taxes::getOne($value);
                        $taxAmount = Taxes::lineItemTaxCalc($tax, $unitPrice, $quantity);
                        $request = new Request("INSERT", "invoice_item_tax");
                        $request->setFauxPost([
                            'invoice_item_id' => $invoiceItemId,
                            'tax_id'          => $tax['tax_id'],
                            'tax_rate'        => $tax['tax_percentage'],
                            'tax_type'        => $tax['type'],
                            'tax_amount'      => $taxAmount
                        ]);
                        // @formatter:on
                        $requests->add($request);
                    }
                }
            }

            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Invoice::invoice_item_tax(): Unable to process requests. Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Get the invoice record by the index_id and current domain_id.
     * @param int|string $indexId
     * @return array empty if no such record otherwise invoices record.
     * @throws PdoDbException
     */
    public static function getInvoiceByIndexId($indexId): array
    {
        global $pdoDb;

        try {
            session_name('SiAuth');
            session_start();

            // If user role is customer or biller, then restrict invoices to those they have access to.
            if ($_SESSION['role_name'] == 'customer') {
                $pdoDb->addSimpleWhere("customer_id", $_SESSION['user_id'], "AND");
            } elseif ($_SESSION['role_name'] == 'biller') {
                $pdoDb->addSimpleWhere("biller_id", $_SESSION['user_id'], "AND");
            }

            $pdoDb->addSimpleWhere('index_id', $indexId, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $rows = $pdoDb->request('SELECT', 'invoices');
        } catch (PdoDbException $pde) {
            error_log("Invoice::  getInvoiceByIndexId() - error: " . $pde->getMessage());
            throw $pde;
        }

        if (empty($rows)) {
            return [];
        }
        return $rows[0];
    }

    /**
     * @param string $que
     * @return array
     * @throws PdoDbException
     */
    public static function getInvoicesWithHtmlTotals(string $que): array
    {
        $results = [];
        if (empty($que)) {
            $rows = self::getAll();
            foreach ($rows as $row) {
                $row['calc_date'] = date('Y-m-d', strtotime($row['date']));
                $row['date'] = Util::date($row['date']);
                $row['total'] = self::getInvoiceTotal($row['id']);
                $row['paid'] = Payment::calcInvoicePaid($row['id']);

                $ageInfo = self::calculateAgeDays(
                    $row['id'],
                    $row['date'],
                    $row['owing'],
                    $row['last_activity_date'],
                    $row['aging_date'],
                    $row['preference_id']
                );

                self::updateAgingValues($row, $ageInfo);

                $results[] = $row;

                if (strpos(strtolower($row['index_id']), strtolower($que)) !== false) {
                    // @formatter:off
                    $total = Util::htmlSafe(number_format($row['total'],2));
                    $paid  = Util::htmlSafe(number_format($row['paid'],2));
                    $owing = Util::htmlSafe(number_format($row['owing'],2));
                    echo "{$row['id']}|" .
                            "<table>" .
                                "<tr>" .
                                    "<td class='details_screen'>{$row['preference']}:</td>" .
                                    "<td>{$row['index_id']}</td>" .
                                    "<td class='details_screen'>Total: </td>" .
                                    "<td>$total</td>" .
                                "</tr>" .
                                "<tr>" .
                                    "<td class='details_screen'>Biller: </td>" .
                                    "<td>{$row['biller']}</td>" .
                                    "<td class='details_screen'>Paid: </td>" .
                                    "<td>$paid</td>" .
                                "</tr>" .
                                "<tr>" .
                                    "<td class='details_screen'>Customer: </td>" .
                                    "<td>{$row['customer']}</td>" .
                                    "<td class='details_screen'>Owing: </td>" .
                                    "<td><u>$owing</u></td>" .
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
     * @param array|string $parms Parameter values required by the specified option.
     * @return Havings havings SQL statement
     * @throws PdoDbException
     */
    public static function buildHavings(string $option, $parms = ""): Havings
    {
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
                default:
                    error_log("Invoice::buildHavings() - Undefined option[$option]");
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::buildHavings() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $havings;
    }

    /**
     * Get the invoice-items associated with a specific invoice.
     * @param int $id
     * @return array
     * @throws PdoDbException
     */
    public static function getInvoiceItems(int $id): array
    {
        global $pdoDb;

        $invoiceItems = [];
        try {
            $pdoDb->addSimpleWhere("invoice_id", $id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $pdoDb->setOrderBy("id");
            $rows = $pdoDb->request("SELECT", "invoice_items");

            foreach ($rows as $invoiceItem) {
                if (isset($invoiceItem['attribute'])) {
                    $invoiceItem['attributeDecode'] = json_decode($invoiceItem['attribute'], true);
                }

                $pdoDb->addSimpleWhere("id", $invoiceItem['product_id'], 'AND');
                $pdoDb->addSimpleWhere('domain_id', DomainId::get());
                $rows = $pdoDb->request("SELECT", "products");
                $invoiceItem['product'] = $rows[0];

                $invItemProdAttrDecode = json_decode($invoiceItem['product']['attribute']);
                $invoiceItem['product']['attributeDecode'] = $invItemProdAttrDecode;

                $invItemProdAttrs = [];
                foreach ($invItemProdAttrDecode as $key => $val) {
                    if ($val) {
                        $invItemProdAttrs[] = ProductAttributes::getOne($key);
                    }
                }

                $invoiceItem['productAttributes'] = $invItemProdAttrs;

                $tax = self::taxesGroupedForInvoiceItem($invoiceItem['id']);
                foreach ($tax as $key => $value) {
                    $invoiceItem['tax'][$key] = $value['tax_id'];
                }
                $invoiceItems[] = $invoiceItem;
            }
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceItems() - id[$id] error: " . $pde->getMessage());
            throw $pde;
        }
        return $invoiceItems;
    }

    /**
     * Get Invoice type.
     * @param int $id Invoice type ID.
     * @return array Associative array for <i>invoice_type</i> record accessed.
     * @throws PdoDbException
     */
    public static function getInvoiceType(int $id): array
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("inv_ty_id", $id);
            $result = $pdoDb->request("SELECT", "invoice_type");
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceType() - id[$id] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $result;
    }

    /**
     * Function getInvoiceTotal
     * @param int $invoiceId Unique ID (si_invoices id value) of invoice for which
     *        totals from si_invoice_items will be summed.
     * @return float
     * @throws PdoDbException
     */
    private static function getInvoiceTotal(int $invoiceId): float
    {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "total", "total"));
            $pdoDb->addSimpleWhere("invoice_id", $invoiceId); // domain_id not needed

            $rows = $pdoDb->request("SELECT", "invoice_items");
            $total = empty($rows[0]['total']) ? 0 : $rows[0]['total'];
        } catch (PdoDbException $pde) {
            error_log("Invoice::getInvoiceTotal() - invoice_id[$invoiceId] - error: " . $pde->getMessage());
            throw $pde;
        }
        return $total;
    }

    /**
     * Purpose: to show a nice summary of total $ for tax for an invoice
     * @param int $invoiceId
     * @return int Count of records found.
     * @throws PdoDbException
     */
    public static function numberOfTaxesForInvoice(int $invoiceId): int
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("item.invoice_id", $invoiceId, 'AND');
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
            error_log("Invoice::numberOfTaxesForInvoice() - invoice_id[$invoiceId] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $count;
    }

    /**
     * Generates a nice summary of total $ for tax for an invoice. Note that only
     * non-zero tax records are returned.
     * @param int $invoiceId The <b>id</b> column for the invoice to get info for.
     * @return array Rows retrieve.
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoice(int $invoiceId): array
    {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "item_tax.tax_amount", "tax_amount"));
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "*", "count"));

            $pdoDb->addToWhere(new WhereItem(false, "item_tax.tax_amount", '<>', 0, false, 'AND'));
            $pdoDb->addSimpleWhere("item.invoice_id", $invoiceId, 'AND');
            $pdoDb->addSimpleWhere('item.domain_id', DomainId::get());

            $jn = new Join("INNER", "invoice_item_tax", "item_tax");
            $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $exprList = [
                new DbField("tax.tax_id", "tax_id"),
                new DbField("tax.tax_description", "tax_name"),
                new DbField("item_tax.tax_rate", "tax_rate")
            ];

            $pdoDb->setSelectList($exprList);
            $pdoDb->setGroupBy($exprList);

            $rows = $pdoDb->request("SELECT", "invoice_items", "item");
        } catch (PdoDbException $pde) {
            error_log("Invoice::taxesGroupedForInvoice() - invoice_id[$invoiceId] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $rows;
    }

    /**
     * Function: taxesGroupedForInvoiceItem
     * Purpose: to show a nice summary of total $ for tax for an invoice item.
     * Used for invoice editing
     * @param int Invoice item ID
     * @return array Items found
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoiceItem(int $invoiceItemId): array
    {
        global $pdoDb;

        try {
            $pdoDb->setSelectList([
                "item_tax.id AS row_id",
                "tax.tax_description AS tax_name",
                "tax.tax_id AS tax_id"
            ]);

            $pdoDb->addSimpleWhere("item_tax.invoice_item_id", $invoiceItemId);

            $jn = new Join("LEFT", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setOrderBy("row_id");

            $rows = $pdoDb->request("SELECT", "invoice_item_tax", "item_tax");
        } catch (PdoDbException $pde) {
            error_log("Invoice::taxesGroupedForInvoiceItem() - invoice_item_id[$invoiceItemId] - error: " . $pde->getMessage());
            throw $pde;
        }
        return $rows;
    }

    /**
     * Retrieve maximum invoice number assigned for a specified preference ID.
     * @param int $preferenceId
     * @return int Maximum invoice number assigned.
     * @throws PdoDbException
     */
    public static function maxIndexIdForPreference(int $preferenceId): int
    {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("COALESCE", "MAX(index_id),0", "maxIndexId"));

            $pdoDb->addSimpleWhere('preference_id', $preferenceId, 'AND');
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $rows = $pdoDb->request("SELECT", "invoices");
            $maxIndexId = $rows[0]['maxIndexId'];
        } catch (PdoDbException $pde) {
            error_log("Invoice::maxIndexId() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $maxIndexId;
    }

    /**
     * Process a recurring item
     * @param int $invoiceId
     * @return int
     * @throws PdoDbException
     */
    public static function recur(int $invoiceId): int
    {
        global $config;

        try {
            $timezone = $config['phpSettingsDateTimezone'];
            $tz = new DateTimeZone($timezone);
            $dtm = new DateTime('now', $tz);
            $dtTime = $dtm->format("Y-m-d H:i:s");
        } catch (Exception $exp) {
            $dtTime = '';
        }

        $invoice = self::getOne($invoiceId);
        $invoiceItems = self::getInvoiceItems($invoiceId);
        // @formatter:off
        $list = [
            'biller_id'     => $invoice['biller_id'],
            'customer_id'   => $invoice['customer_id'],
            'type_id'       => $invoice['type_id'],
            'preference_id' => $invoice['preference_id'],
            'domain_id'     => $invoice['domain_id'],
            'date'          => $dtTime,
            'note'          => $invoice['note'],
            'custom_field1' => $invoice['custom_field1'],
            'custom_field2' => $invoice['custom_field2'],
            'custom_field3' => $invoice['custom_field3'],
            'custom_field4' => $invoice['custom_field4']
        ];
        $newId = self::insert($list);

        // insert each line item
        foreach ($invoiceItems as $invoiceItem) {
            $list = [
                'invoice_id' => $newId,
                'domain_id'  => $invoiceItem['domain_id'],
                'quantity'   => $invoiceItem['quantity'],
                'product_id' => $invoiceItem['product_id'],
                'unit_price' => $invoiceItem['unit_price'],
                'tax_amount' => $invoiceItem['tax_amount'],
                'gross_total'=> $invoiceItem['gross_total'],
                'description'=> $invoiceItem['description'],
                'total'      => $invoiceItem['total'],
                'attribute'  => $invoiceItem['attribute']
            ];

            self::insertItem($list, $invoiceItem['tax_id']);
        }
        // @formatter:on

        return $newId;
    }

}
