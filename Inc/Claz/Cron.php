<?php

namespace Inc\Claz;

use Mpdf\Output\Destination;

use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class Cron
 * @package Inc\Claz
 */
class Cron
{

    /**
     * Retrieve a record for a specified id.
     * @param int $id of record to retrieve.
     * @return array
     */
    public static function getOne(int $id): array
    {
        return self::getCrons($id);
    }

    /**
     * Retrieve all records for the current user's domain
     * @return array
     */
    public static function getAll(): array
    {
        return self::getCrons();
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getAll();
        $tableRows = [];
        foreach ($rows as $row) {
            $action =
                "<a class='index_table' title='{$LANG['view']} {$row['index_name']}' " .
                   "href='index.php?module=cron&amp;view=view&amp;id={$row['id']}'>" .
                    "<img src='images/view.png' alt='{$LANG['view']} {$row['index_name']}'/>" .
                "</a> " .
                "<a class='index_table' title='{$LANG['edit']} {$row['index_name']}' " .
                   "href='index.php?module=cron&amp;view=edit&amp;id={$row['id']}'>" .
                    "<img src='images/edit.png' alt='{$LANG['edit']} {$row['index_name']}'/>" .
                "</a> " .
                "<a class='index_table' title='{$LANG['delete']} {$row['index_name']}' " .
                   "href='index.php?module=cron&amp;view=delete&amp;id={$row['id']}&amp;stage=1&amp;err_message='>" .
                    "<img src='images/delete.png' alt='{$LANG['delete']} {$row['index_name']}'/>" .
                "</a>";

            $tableRows[] = [
                'action' => $action,
                'invoiceId' =>
                    "<a href='index.php?module=invoices&amp;view=quickView&amp;id={$row['invoice_id']}'>" .
                        $row['index_id'] .
                    "</a>",
                'startDate' => $row['start_date'],
                'endDate' => $row['end_date'],
                'recurrenceInfo' => $row['recurrence'] . ' ' . $row['recurrence_type'],
                'emailBillerNice' => $row['email_biller'] == ENABLED ? $LANG['yesUc'] : $LANG['noUc'],
                'emailCustomerNice' => $row['email_customer'] == ENABLED ? $LANG['yesUc'] : $LANG['noUc'],
                'customerName' => $row['name']
            ];
        }

        return $tableRows;
    }

    /**
     * Standard getter for cron records.
     * @param int|null $id If not null, the id of the record to retrieve.
     * @return array
     */
    private static function getCrons(?int $id = null): array
    {
        global $LANG, $pdoDb;

        $crons = [];
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('cron.id', $id, 'AND');
            }
            $pdoDb->addSimpleWhere("cron.domain_id", DomainId::get());

            $fn = new FunctionStmt("CONCAT", "pf.pref_description, ' ', iv.index_id");
            $se = new Select($fn, null, null, null, "index_name");
            $pdoDb->addToSelectStmts($se);

            $jn = new Join('INNER', 'invoices', 'iv');
            $jn->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('INNER', 'customers', 'cust');
            $jn->addSimpleItem("iv.customer_id", new DbField("cust.id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("cust.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join('INNER', 'preferences', 'pf');
            $jn->addSimpleItem("iv.preference_id", new DbField("pf.pref_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("pf.domain_id"));
            $pdoDb->addToJoins($jn);

            $exprList = [
                "cron.id",
                "cron.domain_id",
                "cron.email_biller",
                "cron.email_customer",
                "cron.start_date",
                "cron.end_date",
                "cron.invoice_id",
                "cron.recurrence",
                "cron.recurrence_type",
                "cust.name",
                new DbField("iv.index_id", 'index_id'),
                new DbField("pf.locale", "locale"),
                new DbField("pf.pref_currency_sign", "currency_sign"),
                new DbField("pf.currency_code", "currency_code")
            ];

            $pdoDb->setSelectList($exprList);

            $pdoDb->setGroupBy($exprList);

            $pdoDb->setOrderBy("cron.id");

            $rows = $pdoDb->request("SELECT", "cron", "cron");
            foreach ($rows as $row) {
                $row['email_biller_nice'] = $row['email_biller'] == ENABLED ? $LANG['yesUc'] : $LANG['noUc'];
                $row['email_customer_nice'] = $row['email_customer'] == ENABLED ? $LANG['yesUc'] : $LANG['noUc'];
                $crons[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Cron::getCrons() - Error - " . $pde->getMessage());
        }
        if (empty($crons)) {
            return [];
        }
        return isset($id) ? $crons[0] : $crons;
    }

    /**
     * Insert a new record.
     * @return int ID of inserted record. 0 if insert failed.
     */
    public static function insert(): int
    {
        global $pdoDb;
        $result = null;
        try {
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "cron");
            $result = empty($id) ? null : $id;
        } catch (PdoDbException $pde) {
            error_log("Cron insert error - " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update an existing record.
     * @param int $id
     * @return bool
     */
    public static function update(int $id): bool
    {
        global $pdoDb;
        $result = false;
        try {
            $pdoDb->setExcludedFields(["id", "domain_id"]);
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $result = $pdoDb->request("UPDATE", "cron");
        } catch (PdoDbException $pde) {
            error_log("Cron update error - " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Delete a specific record
     * @param int $id of record to delete.
     * @return bool true if succeeded; otherwise false.
     */
    public static function delete(int $id): bool
    {
        global $pdoDb;

        try {
            $row = self::getOne($id);
            $invoiceId = $row['invoice_id'];

            $pdoDb->begin();
            $pdoDb->addSimpleWhere('cron_invoice_item_id', $invoiceId);
            $result = $pdoDb->request('DELETE', 'cron_invoice_item_tax');
            if ($result) {
                $pdoDb->addSimpleWhere('cron_id', $id);
                $result = $pdoDb->request('DELETE', 'cron_invoice_items');
                if ($result) {
                    $pdoDb->addSimpleWhere('cron_id', $id);
                    $result = $pdoDb->request('DELETE', 'cron_log');
                    if ($result) {
                        $pdoDb->addSimpleWhere("id", $id, "AND");
                        $pdoDb->addSimpleWhere("domain_id", DomainId::get());
                        $result = $pdoDb->request("DELETE", "cron");
                        if ($result) {
                            $pdoDb->commit();
                            return true;
                        }
                    }
                }
            }
        } catch (PdoDbException $pde) {
            error_log("Cron::delete() - error: " . $pde->getMessage());
        }

        try {
            $pdoDb->rollback();
        } catch (PdoDbException $pde) {
            error_log("Cron::delete() - error2: " . $pde->getMessage());
        }
        return false;
    }

    /**
     * Check the src array to determine if the FROM email address is the customer or the biller or both.
     * @param array $srcArray Email send address flags. Index values are "email_customer' and 'email_biller'
     *          tested to see if ENABLED.
     * @param array $customer Customer send address. Multiple addresses are ";" separated.
     * @param array $biller Biller send address. Multiple addresses are ";" separated.
     * @return array To email address array.
     */
    private static function getEmailSendAddresses(array $srcArray, array $customer, array $biller): array
    {
        $emailTo = [];
        if ($srcArray['email_customer'] == ENABLED) {
            $emailTo[$customer['email']] = $customer['name'];
        }

        if ($srcArray['email_biller'] == ENABLED) {
            $emailTo[$biller['email']] = $biller['name'];
        }

        return $emailTo;
    }

    /**
     * @return array
     * @throws PdoDbException
     * @throws Exception
     */
    public static function run(): array
    {
        global $LANG, $pdoDb;

        $result = [];

        $today = date('Y-m-d');
        $rows = self::selectCronsToRun();
        $result['cron_message'] = "Cron started";
        $numberOfCronsRun = 0;
        Log::out("Cron::run() - today[$today] row count[" . count($rows) . "]");

        $idx = 0; // set here so accessible outside of loop
        foreach ($rows as $value) {
            $cronId = $value['id'];
            $domainId = $value['domain_id'];

            $cronLogExists = CronLog::check($pdoDb, $domainId, $cronId, $today);
            Log::out("Cron::run() - cron_id[$cronId] domain_id[$domainId] cronLogExists[$cronLogExists]");

            $idx = 0;
            if (!$cronLogExists) {
                // only proceed if Cron has not been run for today
                $startDate = date('Y-m-d', strtotime($value['start_date']));
                $endDate = $value['end_date'];

                // Seconds in a day = 60 * 60 * 24 = 86400
                $diff = number_format((strtotime($today) - strtotime($startDate)) / 86400);
                Log::out("Cron::run() - start_date[$startDate] end_date($endDate] diff[$diff]");

                // only check if diff is positive
                if ($diff >= 0 && ($endDate == "" || $endDate >= $today)) {
                    $month = false;
                    Log::out("Cron::run() - recurrence_type[{$value['recurrence_type']}]");

                    switch ($value['recurrence_type']) {
                        case 'day':
                            // Calculate number of days passed.
                            $modulus = $diff % $value['recurrence'];
                            $runCron = $modulus == 0;
                            Log::out("Cron::run() - recurrence[{$value['recurrence']}] modulus[$modulus] run_cron[$runCron]");
                            break;

                        case 'week':
                            // Calculate number of weeks passed.
                            $period = $value['recurrence'] * 7;
                            $modulus = $diff % $period;
                            $runCron = $modulus == 0;
                            Log::out("Cron::run() - recurrence[{$value['recurrence']}] period[$period] modulus[$modulus] run_cron[$runCron]");
                            break;

                        case 'month':
                            $month = true;
                        case 'year' :
                            $startDay = date('d', strtotime($value['start_date']));
                            $startMonth = date('m', strtotime($value['start_date']));
                            $startYear = date('Y', strtotime($value['start_date']));
                            $todayDay = date('d');
                            $todayMonth = date('m');
                            $todayYear = date('Y');

                            if ($month) {
                                // Calculate number of month passed.
                                $val = $todayMonth - $startMonth + ($todayYear - $startYear) * 12;
                            } else {
                                // Calculate number of years passed.
                                $val = $todayYear - $startYear;
                            }

                            $modulus = $val % $value['recurrence'];
                            $runCron = $modulus == 0 && $startDay == $todayDay;
                            if (!$month) {
                                $runCron = $runCron && $startMonth == $todayMonth;
                            }
                            Log::out("Cron::run() - start_day[$startDay] start_month[$startMonth] start_year[$startYear]");
                            Log::out("Cron::run() - today_day[$todayDay] today_month[$todayMonth] today_year[$todayYear]");
                            Log::out("Cron::run() - modulus[$modulus] val[$val]");
                            break;

                        default:
                            $runCron = false;
                            break;
                    }

                    // run the recurrence for this invoice
                    Log::out("Cron::run() - run_cron[$runCron]");
                    if ($runCron) {
                        $numberOfCronsRun++;
                        $cronMsg = "Cron ID: $value[id] - Cron for $value[index_name] with ";
                        $cronMsg .= (empty($value['start_date']) ? "no start date" : "start date of $value[start_date] ") . "and ";
                        $cronMsg .= empty($value['end_date']) ? "no end date" : "an end date of $value[end_date] ";
                        $cronMsg .= " that runs each $value[recurrence] $value[recurrence_type], was run today :: Info diff=$diff";
                        $result["cron_message_{$value['id']}"] = $cronMsg;
                        $idx++;

                        // $domainId gets propagated from invoice to be copied from
                        $newInvoiceId = Invoice::recur($value['invoice_id'], $cronId);

                        CronLog::insert($pdoDb, $domainId, $cronId, $today);
                        $invoice = Invoice::getOne($newInvoiceId);
                        $biller = Biller::getOne($invoice['biller_id']);
                        $customer = Customer::getOne($invoice['customer_id']);
                        $preference = Preferences::getOne($invoice['preference_id']);

                        // email invoice
                        if ($value['email_biller'] == ENABLED || $value['email_customer'] == ENABLED) {
                            $export = new Export(Destination::STRING_RETURN);
                            $export->setBiller($biller);
                            $export->setCustomer($customer);
                            $export->setFormat("pdf");
                            $export->setInvoices($invoice);
                            $export->setModule('invoice');
                            $export->setPreference($preference);
                            $pdfString = $export->execute();

                            $emailBody = self::emailBodyGen('cron_invoice', $customer['name'], $invoice['index_name'], $biller['name']);

                            $email = new Email();
                            $email->setBody($emailBody->create());
                            $email->setFormat('cron_invoice');
                            $email->setFrom([$biller['email'] => $biller['name']]);
                            $email->setPdfFileName($export->getFileName() . '.pdf');
                            $email->setPdfString($pdfString);
                            $email->setSubject($email->makeSubject('invoice'));
                            $email->setEmailTo(self::getEmailSendAddresses($value, $customer, $biller));
                            $results = $email->send();
                            $result['email_message'] = $results['message'];
                        }

                        // Check that all details are OK before doing the eway payment
                        $ewayCheck = new Eway ();
                        $ewayCheck->invoice = $invoice;
                        $ewayCheck->customer = $customer;
                        $ewayCheck->biller = $biller;
                        $ewayCheck->preference = $preference;
                        $ewayPreCheck = $ewayCheck->preCheck();

                        // do eway payment
                        if ($ewayPreCheck == 'true') {
                            $eway = new Eway ();
                            $eway->invoice = $invoice;
                            $eway->biller = $biller;
                            $eway->customer = $customer;
                            $paymentId = $eway->payment();
                            $paymentDone = $paymentId !== 'false';

                            // Appears to not be used. Commented out by Rich Rowley 20181104
                            // $pdf_file_name_receipt = 'payment' . $paymentId . '.pdf';
                            if ($paymentDone == 'true') {
                                // Email receipt to biller and customer
                                if ($value['email_biller'] == ENABLED || $value['email_customer'] == ENABLED) {
                                    // Code to email a new copy of the invoice to the customer
                                    $exportRec = new Export(Destination::STRING_RETURN);
                                    $exportRec->setBiller($biller);
                                    $exportRec->setCustomer($customer);
                                    $exportRec->setFormat("pdf");
                                    $exportRec->setInvoices($invoice);
                                    $exportRec->setModule('invoice');
                                    $exportRec->setPreference($preference);
                                    $pdfString = $exportRec->execute();

                                    $emailBodyRec = self::emailBodyGen('cron_invoice_receipt', $customer['name'], $invoice['index_name'], $biller['name']);

                                    $emailRec = new Email();
                                    $emailRec->setBody($emailBodyRec->create());
                                    $emailRec->setFormat('cron_invoice');
                                    $emailRec->setFrom([$biller['email'] => $biller['name']]);
                                    $emailRec->setPdfFileName($exportRec->getFileName() . '.pdf');
                                    $emailRec->setPdfString($pdfString);
                                    $emailRec->setSubject($emailRec->makeSubject('invoice_eway_receipt'));
                                    $emailRec->setEmailTo(self::getEmailSendAddresses($value, $customer, $biller));
                                    $results = $emailRec->send();
                                    $result['email_message'] = $results['message'];
                                }
                            } else {
                                // do email to biller/admin - say error
                                $email = new Email();
                                $email->setFormat('cron_payment');
                                $email->setFrom([$biller['email'] => $biller['name']]);
                                $email->setSubject("Payment failed for $invoice[index_name]");
                                $email->setEmailTo([$biller['email'] => $biller['name']]);

                                $errorMessage = "{$LANG['InvoiceUc']}: {$invoice['index_name']}<br />{$LANG['amountUc']}: {$invoice['total']}<br />";
                                foreach ($eway->getMessage() as $key2 => $value2) {
                                    $errorMessage .= "\n<br>\${$LANG['ewayResponseFields']}[\"$key2\"] = $value2";
                                }
                                $email->setBody($errorMessage);

                                $results = $email->send();
                                $result['email_message'] = $results['message'];
                            }
                        }
                    } else {
                        // Cron not run for this cron_id
                        $cronMsg = "Cron ID: {$value['id']} - Cron for {$value['index_name']} with ";
                        $cronMsg .= (empty($value['start_date']) ? "no start date" : "start date of {$value['start_date']} ") . "and ";
                        $cronMsg .= empty($value['end_date']) ? "no end date" : "an end date of {$value['end_date']} ";
                        $cronMsg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff=$diff";
                        $result["cron_message_{$value['id']}"] = $cronMsg;
                    }
                } else {
                    // Days diff is negative. See what's going on.
                    $cronMsg = "Cron ID: {$value['id']} - NOTE RUN: Not scheduled for today. Cron for {$value['index_name']} with ";
                    $cronMsg .= (empty($value['start_date']) ? "no start date" : "start date of {$value['start_date']} ") . "and ";
                    $cronMsg .= empty($value['end_date']) ? "no end date" : "an end date of {$value['end_date']} ";
                    $cronMsg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff=$diff";
                    $result["cron_message_{$value['id']}"] = $cronMsg;
                }
            } else {
                // Cron has already been run for that id today
                $result["cron_message_{$value['id']}"] = "Cron ID: {$value['id']} - Cron has already been run for domain: {$value['domain_id']} " .
                    "for the date: $today for invoice {$value['invoice_id']}";
                $result['email_message'] = "";
            }
        }

        // no crons scheduled for today
        if ($numberOfCronsRun == 0) {
            $result['id'] = $idx;
            $result['cron_message'] = "No invoices recurred for this Cron run for domain: " . DomainId::get() . " for the date: $today";
            $result['email_message'] = "";
        }

        return $result;
    }

    private static function emailBodyGen(string $type, string $customerName, string $invIndexName, string $billerName): object
    {
        $emailBody = new EmailBody();
        $emailBody->emailType = $type;
        $emailBody->customerName = $customerName;
        $emailBody->invoiceName = $invIndexName;
        $emailBody->billerName = $billerName;
        return $emailBody;
    }

    private static function selectCronsToRun(): array
    {
        global $config, $pdoDb;

        $timezone = $config['phpSettingsDateTimezone'];

        // Use this function to select crons that need to run each day across all domain_id values

        $rows = [];
        try {
            $jn = new Join("INNER", "invoices", "iv");
            $jn->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
            $jn->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "preferences", "pf");
            $jn->addSimpleItem("iv.preference_id", new DbField("pf.pref_id"), "AND");
            $jn->addSimpleItem("iv.domain_id", new DbField("pf.domain_id"));
            $pdoDb->addToJoins($jn);

            $fn = new FunctionStmt("CONCAT", "pf.pref_description, ' ', iv.index_id");
            $se = new Select($fn, null, null, null, "index_name");
            $pdoDb->addToSelectStmts($se);

            $dtm = new DateTime(null, new DateTimeZone($timezone));
            $dt = $dtm->format("Y-m-d");
            $pdoDb->addToWhere(new WhereItem(true, "cron.start_date", "=", "", false, "OR"));
            $pdoDb->addToWhere(new WhereItem(false, "cron.start_date", "<=", $dt, true, "AND"));
            $pdoDb->addToWhere(new WhereItem(true, "cron.end_date", "=", "", false, "OR"));
            $pdoDb->addToWhere(new WhereItem(false, "cron.end_date", ">=", $dt, true, "AND"));

            $pdoDb->addSimpleWhere("cron.domain_id", DomainId::get());

            $pdoDb->setSelectList("cron.*");

            $pdoDb->setGroupBy(["cron.id", "cron.domain_id"]);
            $rows = $pdoDb->request("SELECT", "cron", "cron");
        } catch (PdoDbException $pde) {
            error_log("Cron::get_crons_to_run() - Error: " . $pde->getMessage());
        } catch (Exception $exp) {
            error_log("Cron::get_crons_to_run() - Error: " . $exp->getMessage());
        }
        return $rows;
    }

    /**
     * Get cron invoice. Mimics Invoice::getInvoices() for a specific invoice; in
     * this case, the invoice that the cron is for.
     * @param int $cronId - ID of cron record to get invoice for.
     * @return array Invoice record.
     * @throws PdoDbException
     */
    public static function getCronInvoice(int $cronId): array
    {
        global $pdoDb;

        try {
            $cronRec = self::getOne($cronId);
            if (empty($cronRec)) {
                return [];
            }

            $invoiceId = $cronRec['invoice_id'];
            $invoice = Invoice::getOne($invoiceId);

            $pdoDb->addSimpleWhere('iv.id', $invoiceId);

            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.gross_total,0)", "gross"));
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.total,0)", "total"));
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "COALESCE(ii.tax_amount,0)", "total_tax"));

            $jn = new Join("LEFT", "cron_invoice_items", "ii");
            $jn->addSimpleItem("ii.cron_id", $cronId);
            $pdoDb->addToJoins($jn);

            $pdoDb->setSelectList("iv.id");

            $rows = $pdoDb->request("SELECT", "invoices", "iv");

            if (empty($rows)) {
                return [];
            }

            $row = $rows[0];
            $invoice['gross'] += $row['gross'];
            $invoice['total'] += $row['total'];
            $invoice['total_tax'] += $row['total_tax'];

            $invoiceTaxesGrouped = $invoice['tax_grouped'];
            $cronTaxesGrouped = self::taxesGroupedForInvoice($cronId);

            $items = [];
            foreach ($invoiceTaxesGrouped as $invItem) {
                $items[$invItem['tax_name']] = $invItem;
            }

            foreach ($cronTaxesGrouped as $cronItem) {
                if (empty($items[$cronItem['tax_name']])) {
                    $items[$cronItem['tax_name']] = $cronItem;
                } else {
                    $items[$cronItem['tax_name']]['tax_amount'] += $cronItem['tax_amount'];
                    $items[$cronItem['tax_name']]['count'] += $cronItem['count'];
                }
            }

            ksort($items);

            $grouped = [];
            foreach ($items as $item) {
                $grouped[] = $item;
            }
            $invoice['tax_grouped'] = $grouped;

        } catch (PdoDbException $pde) {
            error_log("Cron::getCronInvoice() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $invoice;
    }

    /**
     * Get the cron_invoice_items associated with a specific cron record.
     * @param int $cronId ID of cron record.
     * @param int $domainId Value from invoice referenced by cron.
     * @return array
     * @throws PdoDbException
     */
    public static function getCronInvoiceItems(int $cronId, int $domainId): array
    {
        global $pdoDb;

        $cronInvoiceItems = [];
        try {
            $pdoDb->addSimpleWhere("cron_id", $cronId);
            $pdoDb->setOrderBy("id");
            $rows = $pdoDb->request("SELECT", "cron_invoice_items");

            foreach ($rows as $cronInvoiceItem) {
                $cronInvoiceItem['domain_id'] = $domainId;

                if (isset($cronInvoiceItem['attribute'])) {
                    $cronInvoiceItem['attributeDecode'] = json_decode($cronInvoiceItem['attribute'], true);
                }

                $pdoDb->addSimpleWhere("id", $cronInvoiceItem['product_id'], 'AND');
                $pdoDb->addSimpleWhere('domain_id', DomainId::get());
                $rows = $pdoDb->request("SELECT", "products");
                $cronInvoiceItem['product'] = $rows[0];

                $cronInvItemProdAttrDecode = json_decode($cronInvoiceItem['product']['attribute']);
                $cronInvoiceItem['product']['attributeDecode'] = $cronInvItemProdAttrDecode;

                $cronInvItemProdAttrs = [];
                foreach ($cronInvItemProdAttrDecode as $key => $val) {
                    if ($val) {
                        $cronInvItemProdAttrs[] = ProductAttributes::getOne($key);
                    }
                }

                $cronInvoiceItem['productAttributes'] = $cronInvItemProdAttrs;

                $tax = self::taxesGroupedForInvoiceItem($cronInvoiceItem['id']);
                foreach ($tax as $key => $value) {
                    $cronInvoiceItem['tax'][$key] = $value['tax_id'];
                }

                $cronInvoiceItems[] = $cronInvoiceItem;
            }
        } catch (PdoDbException $pde) {
            error_log("Cron::getInvoiceItems() - cronId[$cronId] error: " . $pde->getMessage());
            throw $pde;
        }

        return $cronInvoiceItems;
    }

    /**
     * Remove invoice items for specific cron ID. In cron_invoice_items
     * and cron_invoice_item_tax tables associated with a $cronId.
     * @param int $cronId to remove cron information for.
     * @throws PdoDbException thrown if issue arises.
     */
    public static function deleteCronInvoiceItems(int $cronId)
    {
        global $config, $pdoDb;

        try {
            $pdoDb->addSimpleWhere('cron_id', $cronId);
            $pdoDb->setSelectList('id');
            $rows = $pdoDb->request("SELECT", "cron_invoice_items");

            $requests = new Requests($config);

            foreach ($rows as $row) {
                $request = new Request("DELETE", "cron_invoice_item_tax");
                $request->addSimpleWhere("cron_invoice_item_id", $row['id']);
                $requests->add($request);
            }

            $request = new Request("DELETE", "cron_invoice_items");
            $request->addSimpleWhere("cron_id", $cronId);
            $requests->add($request);
            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Cron::deleteCronInvoiceItems() - Failed delete. Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Generates a nice summary of total $ for tax for an invoice. Note that only
     * non-zero tax records are returned.
     * @param int $cronId id of cron to get all tax info for.
     * @return array Rows retrieve.
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoice(int $cronId): array
    {
        global $pdoDb;

        try {
            $pdoDb->addToFunctions(new FunctionStmt("SUM", "item_tax.tax_amount", "tax_amount"));
            $pdoDb->addToFunctions(new FunctionStmt("COUNT", "*", "count"));

            $pdoDb->addToWhere(new WhereItem(false, "item_tax.tax_amount", '<>', 0, false, 'AND'));
            $pdoDb->addSimpleWhere("item.cron_id", $cronId);

            $jn = new Join("INNER", "cron_invoice_item_tax", "item_tax");
            $jn->addSimpleItem("item_tax.cron_invoice_item_id", new DbField("item.id"));
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

            $rows = $pdoDb->request("SELECT", "cron_invoice_items", "item");
        } catch (PdoDbException $pde) {
            error_log("Cron::taxesGroupedForInvoice() - cron_id[$cronId] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $rows;
    }

    /**
     * Function: taxesGroupedForInvoiceItem
     * Purpose: to show a nice summary of total $ for tax for an invoice item.
     * @param int $cronInvoiceItemId Invoice item ID
     * @return array Items found
     * @throws PdoDbException
     */
    private static function taxesGroupedForInvoiceItem(int $cronInvoiceItemId): array
    {
        global $pdoDb;

        try {
            $pdoDb->setSelectList([
                "item_tax.id AS row_id",
                "tax.tax_description AS tax_name",
                "tax.tax_id AS tax_id"
            ]);

            $pdoDb->addSimpleWhere("item_tax.cron_invoice_item_id", $cronInvoiceItemId);

            $jn = new Join("LEFT", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setOrderBy("row_id");

            $rows = $pdoDb->request("SELECT", "cron_invoice_item_tax", "item_tax");
        } catch (PdoDbException $pde) {
            error_log("Invoice::taxesGroupedForInvoiceItem() - cron_invoice_item_id[$cronInvoiceItemId] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $rows;
    }

    /**
     * Purpose: to show a nice summary of total $ for tax for an invoice
     * @param int $cronId
     * @return int Count of records found.
     * @throws PdoDbException
     */
    public static function numberOfTaxesForInvoice(int $cronId): int
    {
        global $pdoDb;

        try {
            $pdoDb->addSimpleWhere("item.cron_id", $cronId);

            $pdoDb->addToFunctions(new FunctionStmt("DISTINCT", new DbField("tax.tax_id")));

            $jn = new Join("INNER", "cron_invoice_item_tax", "item_tax");
            $jn->addSimpleItem("item_tax.cron_invoice_item_id", new DbField("item.id"));
            $pdoDb->addToJoins($jn);

            $jn = new Join("INNER", "tax", "tax");
            $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
            $pdoDb->addToJoins($jn);

            $pdoDb->setGroupBy("tax.tax_id");

            $rows = $pdoDb->request("SELECT", "cron_invoice_items", "item");
            $count = count($rows);
        } catch (PdoDbException $pde) {
            error_log("Cron::numberOfTaxesForInvoice() - cron_id[$cronId] - error: " . $pde->getMessage());
            throw $pde;
        }

        return $count;
    }

    /**
     * Insert a new <b>cron_invoice_items</b> record.
     * @param int $cronId
     * @param float $quantity
     * @param int $productId
     * @param array $taxIds
     * @param string $description
     * @param float $unitPrice
     * @param array|null $attribute
     * @return int <b>id</b> of new <i>cron_invoice_items</i> record. 0 if insert failed.
     * @throws PdoDbException
     */
    public static function insertCronInvoiceItem(int $cronId, float $quantity, int $productId,
                                                 array $taxIds, string $description = "",
                                                 float $unitPrice = 0, ?array $attribute = null): int
    {
        global $LANG;

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
        $list = ['cron_id' => $cronId,
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
     * Insert a new invoice_item and the invoice_item_tax records.
     * @param array $list Associative array keyed by field name with its assigned value.
     * @param array|null $taxIds
     * @return int Unique ID of the new invoice_item record.
     * @throws PdoDbException
     */
    private static function insertItem(array $list, ?array $taxIds): int
    {
        global $pdoDb;

        try {
            $pdoDb->setFauxPost($list);
            $pdoDb->setExcludedFields("id");
            $id = $pdoDb->request("INSERT", "cron_invoice_items");

            self::chgCronInvoiceItemTax($id, $taxIds, $list['unit_price'], $list['quantity'], false);
        } catch (PdoDbException $pde) {
            error_log("Invoice::insertItem() - Error: " . $pde->getMessage());
            throw $pde;
        }
        return $id;
    }

    /**
     * Update cron_invoice_items table for a specific entry.
     * @param int $id Unique id for the record to be updated.
     * @param float $quantity Number of items
     * @param int $productId Unique id of the si_products record for this item.
     * @param array  $taxIds Unique id for the taxes to apply to this line item.
     * @param string $description Extended description for this line item.
     * @param float $unitPrice Price of each unit of this item.
     * @param array|null $attribute Attributes for invoice.
     * @throws PdoDbException
     */
    public static function updateCronInvoiceItem(int $id, float $quantity, int $productId, array $taxIds,
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
            $pdoDb->setExcludedFields(["id", "cron_id"]);
            $pdoDb->request("UPDATE", "cron_invoice_items");
            // @formatter:on

            self::chgCronInvoiceItemTax($id, $taxIds, $unitPrice, $quantity, true);
        } catch (PdoDbException $pde) {
            error_log("Cron::updateCronInvoiceItem() - Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Insert/update the multiple taxes for an invoice line item.
     * @param int $cronInvoiceItemId
     * @param array|null $lineItemTaxIds
     * @param float $unitPrice
     * @param float $quantity
     * @param bool $update
     * @throws PdoDbException
     */
    public static function chgCronInvoiceItemTax(int $cronInvoiceItemId, ?array $lineItemTaxIds, float $unitPrice,
                                             float $quantity, bool $update): void
    {
        global $config;

        try {
            $requests = new Requests($config);
            if ($update) {
                $request = new Request("DELETE", "cron_invoice_item_tax");
                $request->addSimpleWhere("cron_invoice_item_id", $cronInvoiceItemId);
                $requests->add($request);
            }

            if (!empty($lineItemTaxIds)) {
                foreach ($lineItemTaxIds as $value) {
                    if (!empty($value)) {
                        // @formatter:off
                        $tax = Taxes::getOne($value);
                        $taxAmount = Taxes::lineItemTaxCalc($tax, $unitPrice, $quantity);
                        $request = new Request("INSERT", "cron_invoice_item_tax");
                        $request->setFauxPost([
                            'cron_invoice_item_id' => $cronInvoiceItemId,
                            'tax_id'               => $tax['tax_id'],
                            'tax_rate'             => $tax['tax_percentage'],
                            'tax_type'             => $tax['type'],
                            'tax_amount'           => $taxAmount
                        ]);
                        // @formatter:on
                        $requests->add($request);
                    }
                }
            }

            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Cron::chgCronInvoiceItemTax(): Unable to process requests. Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Deletes a specific cron_invoice_items record and its tax info from the database.
     * @param int|string $id of cron_invoice_items record to delete.
     * @return bool true if delete processed, false if not.
     */
    public static function deleteCronInvoiceItem($id): bool
    {
        global $pdoDb;

        try {
            $pdoDb->begin();

            $pdoDb->addSimpleWhere("cron_invoice_item_id", $id);
            $result = $pdoDb->request("DELETE", "cron_invoice_item_tax");
            if ($result) {
                $pdoDb->addSimpleWhere("id", $id);
                $result = $pdoDb->request("DELETE", "cron_invoice_items");
                if ($result) {
                    $pdoDb->commit();
                    return true;
                }
            }
        } catch (PdoDbException $pde) {
            error_log("Cron::deleteCronInvoiceItem() - error: " . $pde->getMessage());
        }

        try {
            $pdoDb->rollback();
        } catch (PdoDbException $pde) {
            error_log("Cron::deleteCronInvoiceItem() - error2: " . $pde->getMessage());
        }

        return false;
    }

}
