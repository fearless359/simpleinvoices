<?php
namespace Inc\Claz;

use Mpdf\Output\Destination;

use \Exception;
use \Zend_Log;

/**
 * Class Cron
 * @package Inc\Claz
 */
class Cron {

    /**
     * Retrieve a record for a specified id.
     * @param int $id of record to retrieve.
     * @return array
     */
    public static function getOne($id) {
        return self::getCrons($id);
    }

    /**
     * Retrieve all records for the domain
     * @return array|mixed
     */
    public static function getAll() {
        return self::getCrons();
    }

    /**
     * @param int $id If not null, the id of the record to retrieve.
     * @return array|mixed
     */
    private static function getCrons($id = null) {
        global $LANG, $pdoDb;

        $crons = array();
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

            $expr_list = array(
                "cron.id",
                "cron.domain_id",
                "cron.email_biller",
                "cron.email_customer",
                "cron.start_date",
                "cron.end_date",
                "cron.invoice_id",
                "cron.recurrence",
                "cron.recurrence_type",
                "cust.name"
            );

            $pdoDb->setSelectList($expr_list);

            $pdoDb->setGroupBy($expr_list);

            $pdoDb->setOrderBy("cron.id");

            $rows = $pdoDb->request("SELECT", "cron", "cron");

            foreach ($rows as $row) {
                $row['email_biller_nice']   = ($row['email_biller']   == ENABLED ? $LANG['yes_uppercase'] : $LANG['no_uppercase']);
                $row['email_customer_nice'] = ($row['email_customer'] == ENABLED ? $LANG['yes_uppercase'] : $LANG['no_uppercase']);
                $crons[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Cron::getCrons() - Error - " . $pde->getMessage());
        }
        if (empty($crons)) {
            return array();
        }
        return (isset($id) ? $crons[0] : $crons);
    }

    /**
     * @return int ID of inserted record. 0 if insert failed.
     */
    public static function insert() {
        global $pdoDb;
        $result = 0;
        try {
            $pdoDb->setExcludedFields("id");
            $result = $pdoDb->request("INSERT", "cron");
        } catch (PdoDbException $pde) {
            error_log("Cron insert error - " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public static function update($id) {
        global $pdoDb;
        try {
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $result = $pdoDb->request("UPDATE", "cron");
        } catch (PdoDbException $pde) {
            error_log("Cron update error - " . $pde->getMessage());
            return false;
        }
        return $result;
    }

    /**
     * Delete a specific record
     * @param int $id of record record to delete.
     * @return bool true if succeeded; otherwise false.
     */
    public static function delete($id) {
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

        try{
            $pdoDb->rollback();
        } catch (PdoDbException $pde) {
            error_log("Cron::delete() - error2: " . $pde->getMessage());
        }
        return false;
    }

    /**
     * @param $src_array
     * @param $customer_email
     * @param $biller_email
     * @return array
     */
    private static function getEmailSendAddresses($src_array, $customer_email, $biller_email) {
        $email_to_addresses = array ();
        if ($src_array['email_customer'] == ENABLED) {
            self::breakMultiEmail($email_to_addresses, $customer_email);
        }
        if ($src_array['email_biller'] == ENABLED) {
            self::breakMultiEmail($email_to_addresses, $biller_email);
        }
        return $email_to_addresses;
    }

    /**
     * @param array $emailAddresses Array to update
     * @param string $emailAddrLine Email address line with one or more email addresses separated
     *          by a semi-colon.
     */
    private static function breakMultiEmail(&$emailAddresses, $emailAddrLine) {
        $parts = explode(';', $emailAddrLine);
        foreach ($parts as $part) {
            $emailAddresses[] = $part;
        }
    }

    /**
     * @return array
     * @throws
     */
    public static function run() {
        global $pdoDb;
        $result = array();

        $today = date('Y-m-d');
        $rows  = self::select_crons_to_run();
        $result['cron_message'] = "Cron started";
        $number_of_crons_run = "0";
        $i = 0; // set here so accessible outside of the loop
        Log::out("Cron::run() - today[$today] row count[" . count($rows) . "]", Zend_Log::DEBUG);

        foreach($rows as $value) {
            $cron_id   = $value['id'];
            $domain_id = $value['domain_id'];

            $check_cron_log = CronLog::check($pdoDb, $domain_id, $cron_id, $today);
            Log::out("Cron::run() - cron_id[$cron_id] domain_id[$domain_id] check_cron_log[$check_cron_log]", Zend_Log::DEBUG);

            $i = "0";
            if ($check_cron_log == 0) {
                // only proceed if Cron has not been run for today
                $start_date = date('Y-m-d', strtotime($value['start_date']));
                $end_date   = $value['end_date'];

                // Seconds in a day = 60 * 60 * 24 = 86400
                $diff = number_format((strtotime($today) - strtotime($start_date)) / 86400, 0);
                Log::out("Cron::run() - start_date[$start_date] end_date($end_date] diff[$diff]", Zend_Log::DEBUG);

                // only check if diff is positive
                if (($diff >= 0) && ($end_date == "" || $end_date >= $today)) {
                    $month = false;
                    Log::out("Cron::run() - recurrence_type[{$value['recurrence_type']}]", Zend_Log::DEBUG);

                    switch($value['recurrence_type']) {
                        case 'day':
                            // Calculate number of days passed.
                            $modulus = $diff % $value['recurrence'];
                            $run_cron = ($modulus == 0);
                            Log::out("Cron::run() - recurrence[{$value['recurrence']}] modulus[$modulus] run_cron[$run_cron]", Zend_Log::DEBUG);
                            break;

                        case 'week':
                            // Calculate number of weeks passed.
                            $period   = $value['recurrence'] * 7;
                            $modulus  = $diff % $period;
                            $run_cron = ($modulus == 0);
                            Log::out("Cron::run() - recurrence[{$value['recurrence']}] period[$period] modulus[$modulus] run_cron[$run_cron]", Zend_Log::DEBUG);
                            break;

                        case 'month':
                            $month = true;
                        case 'year' :
                            $start_day   = date('d', strtotime($value['start_date']));
                            $start_month = date('m', strtotime($value['start_date']));
                            $start_year  = date('Y', strtotime($value['start_date']));
                            $today_day   = date('d');
                            $today_month = date('m');
                            $today_year  = date('Y');

                            if ($month) {
                                // Calculate number of month passed.
                                $val  = ($today_month - $start_month) + (($today_year - $start_year) * 12);
                            } else {
                                // Calculate number of years passed.
                                $val = $today_year - $start_year;
                            }

                            $modulus = $val % $value['recurrence'];
                            $run_cron = ($modulus == 0 && $start_day == $today_day);
                            if (!$month) {
                                $run_cron = ($run_cron && $start_month == $today_month);
                            }
                            Log::out("Cron::run() - start_day[$start_day] start_month[$start_month] start_year[$start_year]", Zend_Log::DEBUG);
                            Log::out("Cron::run() - today_day[$today_day] today_month[$today_month] today_year[$today_year]", Zend_Log::DEBUG);
                            Log::out("Cron::run() - modulus[$modulus] val[$val]", Zend_Log::DEBUG);
                            break;

                        default:
                            $run_cron = false;
                            break;
                    }

                    // run the recurrence for this invoice
                    Log::out("Cron::run() - run_cron[$run_cron]", Zend_Log::DEBUG);
                    if ($run_cron) {
                        $number_of_crons_run++;
                        $cron_msg = "Cron ID: $value[id] - Cron for $value[index_name] with ";
                        $cron_msg .= (empty($value['start_date']) ? "no start date" : "start date of $value[start_date] ") . "and ";
                        $cron_msg .= (empty($value['end_date']  ) ? "no end date"   : "an end date of $value[end_date] ");
                        $cron_msg .= " that runs each $value[recurrence] $value[recurrence_type], was run today :: Info diff=$diff";
                        $result["cron_message_{$value['id']}"] = $cron_msg;
                        $i++;

                        // $domain_id gets propagated from invoice to be copied from
                        $new_invoice_id = Invoice::recur ($value['invoice_id']);

                        CronLog::insert($pdoDb, $domain_id, $cron_id, $today);
                        $invoice = Invoice::getOne($new_invoice_id);
                        $preference = Preferences::getOne($invoice['preference_id']);
                        $biller = Biller::getOne($invoice['biller_id']);
                        $customer = Customer::getOne($invoice['customer_id']);
                        $spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
                        $pdf_file_name_invoice = $spc2us_pref . ".pdf";

                        // email invoice
                        if (($value['email_biller'] == ENABLED) || ($value['email_customer'] == ENABLED)) {
                            $export = new Export(Destination::STRING_RETURN);
                            $export->setFormat("pdf");
                            $export->setId($invoice['id']);
                            $export->setModule('invoice');
                            $pdf_string = $export->execute();

                            // $attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);

                            $email_body = new EmailBody();
                            $email_body->email_type    = 'cron_invoice';
                            $email_body->customer_name = $customer['name'];
                            $email_body->invoice_name  = $invoice['index_name'];
                            $email_body->biller_name   = $biller['name'];

                            $email = new Email();
                            $email->setBody($email_body->create());
                            $email->setFormat('cron_invoice');
                            $email->setFrom($biller['email']);
                            $email->setFromFriendly($biller['name']);
                            $email->setPdfFileName($export->getFileName() . '.pdf');
                            $email->setPdfString($pdf_string);
                            $email->setSubject($email->makeSubject('invoice'));
                            $email->setTo(self::getEmailSendAddresses($value, $customer['email'], $biller['email']));
                            $results = $email->send();
                            $result['email_message'] = $results['message'];
                        }

                        // Check that all details are OK before doing the eway payment
                        $eway_check = new Eway ();
                        $eway_check->domain_id  = $domain_id;
                        $eway_check->invoice    = $invoice;
                        $eway_check->customer   = $customer;
                        $eway_check->biller     = $biller;
                        $eway_check->preference = $preference;
                        $eway_pre_check         = $eway_check->pre_check ();

                        // do eway payment
                        if ($eway_pre_check == 'true') {
                            $eway = new Eway ();
                            $eway->domain_id = $domain_id;
                            $eway->invoice   = $invoice;
                            $eway->biller    = $biller;
                            $eway->customer  = $customer;
                            $payment_id      = $eway->payment ();
                            $payment_done    = ($payment_id !== false);

                            // Appears to not be used. Commented out by Rich Rowley 20181104
                            // $pdf_file_name_receipt = 'payment' . $payment_id . '.pdf';
                            if ($payment_done == 'true') {
                                // Email receipt to biller and customer
                                if ($value['email_biller'] == ENABLED || $value['email_customer'] == ENABLED) {
                                     // Code to email a new copy of the invoice to the customer
                                    $export_rec = new Export(Destination::STRING_RETURN);
                                    $export_rec->setFormat("pdf");
                                    $export_rec->setId($invoice['id']);
                                    $export_rec->setModule('invoice');

                                    $pdf_string = $export_rec->execute();

                                    $email_body_rec = new EmailBody();
                                    $email_body_rec->email_type    = 'cron_invoice_receipt';
                                    $email_body_rec->customer_name = $customer['name'];
                                    $email_body_rec->invoice_name  = $invoice['index_name'];
                                    $email_body_rec->biller_name   = $biller['name'];

                                    $email_rec = new Email();
                                    $email_rec->setBody($email_body_rec->create());
                                    $email_rec->setFormat('cron_invoice');
                                    $email_rec->setFrom($biller['email']);
                                    $email_rec->setFromFriendly($biller['name']);
                                    $email_rec->setPdfFileName($export_rec->getFileName() . '.pdf');
                                    $email_rec->setPdfString($pdf_string);
                                    $email_rec->setSubject($email_rec->makeSubject('invoice_eway_receipt'));
                                    $email_rec->setTo(self::getEmailSendAddresses($value, $customer['email'], $biller['email']));
                                    $results = $email_rec->send();
                                    $result['email_message']  = $results['message'];
                                }
                            } else {
                                // do email to biller/admin - say error
                                $email = new Email();
                                $email->setFormat('cron_payment');
                                $email->setFrom($biller['email']);
                                $email->setFromFriendly($biller['name']);
                                $email->setSubject("Payment failed for $invoice[index_name]");
                                $email->setTo($biller['email']);

                                $error_message = "Invoice: {$invoice['index_name']}<br />Amount: {$invoice['total']}<br />";
                                foreach($eway->get_message () as $key2 => $value2) {
                                    $error_message .= "\n<br>\$ewayResponseFields[\"{$key2}\"] = $value2";
                                }
                                $email->setBody($error_message);

                                $results = $email->send();
                                $result['email_message'] = $results['message'];
                            }
                        }
                    } else {
                        // Cron not run for this cron_id
                        $cron_msg = "Cron ID: {$value['id']} - Cron for {$value['index_name']} with ";
                        $cron_msg .= (empty($value['start_date']) ? "no start date" : "start date of {$value['start_date']} ") . "and ";
                        $cron_msg .= (empty($value['end_date']  ) ? "no end date"   : "an end date of {$value['end_date']} ");
                        $cron_msg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff={$diff}";
                        $result["cron_message_{$value['id']}"] = $cron_msg;
                    }
                } else {
                    // days diff is negative - whats going on
                    $cron_msg = "Cron ID: {$value['id']} - NOTE RUN: Not scheduled for today. Cron for {$value['index_name']} with ";
                    $cron_msg .= (empty($value['start_date']) ? "no start date" : "start date of {$value['start_date']} ") . "and ";
                    $cron_msg .= (empty($value['end_date']  ) ? "no end date"   : "an end date of {$value['end_date']} ");
                    $cron_msg .= " that runs each {$value['recurrence']} {$value['recurrence_type']}, did not recur today :: Info diff={$diff}";
                    $result["cron_message_{$value['id']}"] = $cron_msg;
                }
            } else {
                // Cron has already been run for that id today
                $result["cron_message_{$value['id']}"] = "Cron ID: {$value['id']} - Cron has already been run for domain: {$value['domain_id']} " .
                                                         "for the date: {$today} for invoice {$value['invoice_id']}";
                $result['email_message'] = "";
            }
        }

        // no crons scheduled for today
        if ($number_of_crons_run == '0') {
            $result['id'] = $i;
            $result['cron_message'] = "No invoices recurred for this Cron run for domain: " . DomainId::get() . " for the date: {$today}";
            $result['email_message'] = "";
        }
        return $result;
    }

    /**
     * @return array|mixed
     */
    public static function select_crons_to_run() {
        global $config, $pdoDb;

        $timezone = $config->phpSettings->date->timezone;

        // Use this function to select crons that need to run each day across all domain_id values

        $rows = array();
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

            $dtm = new \DateTime(null, new \DateTimeZone($timezone));
            $dt = $dtm->format("Y-m-d");
            $pdoDb->addToWhere(new WhereItem(true, "cron.start_date", "=", "", false, "OR"));
            $pdoDb->addToWhere(new WhereItem(false, "cron.start_date", "<=", $dt, true, "AND"));
            $pdoDb->addToWhere(new WhereItem(true, "cron.end_date", "=", "", false, "OR"));
            $pdoDb->addToWhere(new WhereItem(false, "cron.end_date", ">=", $dt, true, "AND"));

            $pdoDb->addSimpleWhere("cron.domain_id", DomainId::get());

            $pdoDb->setSelectList("cron.*");

            $pdoDb->setGroupBy(array("cron.id", "cron.domain_id"));
            $rows = $pdoDb->request("SELECT", "cron", "cron");
        } catch (PdoDbException $pde) {
            error_log("Cron::get_crons_to_run() - Error: " . $pde->getMessage());
        } catch (Exception $e) {
            error_log("Cron::get_crons_to_run() - Error: " . $e->getMessage());
        }
        return $rows;
    }

}
