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
                new DbField("iv.index_id", 'index_id')
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
     * @param $id
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
     * @param int $id of record record to delete.
     * @return bool true if succeeded; otherwise false.
     */
    public static function delete(int $id): bool
    {
        global $pdoDb;

        try {
            $pdoDb->begin();
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
     * @param array $srcArray Email send address flags. Index values are "email_customer' and 'email_biller'
     *          tested to see if ENABLED.
     * @param string $customerEmail Customer send address. Multiple addresses are ";" separated.
     * @param string $billerEmail Biller send address. Multiple addresses are ";" separated.
     * @return string To email address string. Multiple email addresses are ";" separated.
     */
    private static function getEmailSendAddresses(array $srcArray, string $customerEmail, string $billerEmail): string
    {
        $emailTo = "";
        if ($srcArray['email_customer'] == ENABLED) {
            $emailTo = $customerEmail;
        }

        if ($srcArray['email_biller'] == ENABLED) {
            if (!empty($emailTo)) {
                $emailTo .= ";";
            }

            $emailTo .= $billerEmail;
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

        $idx = 0; // set here so accessible outside of the loop
        foreach ($rows as $value) {
            $cronId = $value['id'];
            $domainId = $value['domain_id'];

            $checkCronLog = CronLog::check($pdoDb, $domainId, $cronId, $today);
            Log::out("Cron::run() - cron_id[$cronId] domain_id[$domainId] checkCronLog[$checkCronLog]");

            $idx = 0;
            if (!$checkCronLog) {
                // only proceed if Cron has not been run for today
                $startDate = date('Y-m-d', strtotime($value['start_date']));
                $endDate = $value['end_date'];

                // Seconds in a day = 60 * 60 * 24 = 86400
                $diff = number_format((strtotime($today) - strtotime($startDate)) / 86400, 0);
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
                        $newInvoiceId = Invoice::recur($value['invoice_id']);

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
                            $email->setFrom($biller['email']);
                            $email->setFromFriendly($biller['name']);
                            $email->setPdfFileName($export->getFileName() . '.pdf');
                            $email->setPdfString($pdfString);
                            $email->setSubject($email->makeSubject('invoice'));
                            $email->setEmailTo(self::getEmailSendAddresses($value, $customer['email'], $biller['email']));
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
                            $paymentDone = $paymentId !== false;

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
                                    $emailRec->setFrom($biller['email']);
                                    $emailRec->setFromFriendly($biller['name']);
                                    $emailRec->setPdfFileName($exportRec->getFileName() . '.pdf');
                                    $emailRec->setPdfString($pdfString);
                                    $emailRec->setSubject($emailRec->makeSubject('invoice_eway_receipt'));
                                    $emailRec->setEmailTo(self::getEmailSendAddresses($value, $customer['email'], $biller['email']));
                                    $results = $emailRec->send();
                                    $result['email_message'] = $results['message'];
                                }
                            } else {
                                // do email to biller/admin - say error
                                $email = new Email();
                                $email->setFormat('cron_payment');
                                $email->setFrom($biller['email']);
                                $email->setFromFriendly($biller['name']);
                                $email->setSubject("Payment failed for $invoice[index_name]");
                                $email->setEmailTo($biller['email']);

                                $errorMessage = "Invoice: {$invoice['index_name']}<br />Amount: {$invoice['total']}<br />";
                                foreach ($eway->getMessage() as $key2 => $value2) {
                                    $errorMessage .= "\n<br>\${$LANG['ewayResponseFields']}[\"{$key2}\"] = $value2";
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
                        $cronMsg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff={$diff}";
                        $result["cron_message_{$value['id']}"] = $cronMsg;
                    }
                } else {
                    // days diff is negative - whats going on
                    $cronMsg = "Cron ID: {$value['id']} - NOTE RUN: Not scheduled for today. Cron for {$value['index_name']} with ";
                    $cronMsg .= (empty($value['start_date']) ? "no start date" : "start date of {$value['start_date']} ") . "and ";
                    $cronMsg .= empty($value['end_date']) ? "no end date" : "an end date of {$value['end_date']} ";
                    $cronMsg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff={$diff}";
                    $result["cron_message_{$value['id']}"] = $cronMsg;
                }
            } else {
                // Cron has already been run for that id today
                $result["cron_message_{$value['id']}"] = "Cron ID: {$value['id']} - Cron has already been run for domain: {$value['domain_id']} " .
                    "for the date: {$today} for invoice {$value['invoice_id']}";
                $result['email_message'] = "";
            }
        }

        // no crons scheduled for today
        if ($numberOfCronsRun == 0) {
            $result['id'] = $idx;
            $result['cron_message'] = "No invoices recurred for this Cron run for domain: " . DomainId::get() . " for the date: {$today}";
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

}
