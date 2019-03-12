<?php
namespace Inc\Claz;

use Zend_Log;

/**
 * Class Export
 * @package Inc\Claz
 */
class Export
{
    private $biller;
    private $biller_id;
    private $customer;
    private $customer_id;
    private $destination;
    private $do_not_filter_by_date;
    private $end_date;
    private $file_name;
    private $file_type;
    private $format;
    private $id;
    private $invoice;
    private $module;
    private $preference;
    private $show_only_unpaid;
    private $start_date;

    /**
     * Export constructor.
     * @param string $destination Set to Destination::DOWNLOAD for file or
     *      Destination::STRING for email attachment.
     */
    public function __construct(string $destination)
    {
        // @formatter:off
        $this->biller                = null;
        $this->biller_id             = null;
        $this->customer              = null;
        $this->customer_id           = null;
        $this->destination           = $destination;
        $this->do_not_filter_by_date = "no";
        $this->end_date              = "";
        $this->file_name             = "";
        $this->file_type             = "";
        $this->format                = "";
        $this->id                    = null;
        $this->invoice               = null;
        $this->module                = "";
        $this->preference            = null;
        $this->show_only_unpaid      = "no";
        $this->start_date            = "";
        // @formatter:on
    }

    /**
     * @param $data
     * @return string/null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     */
    private function showData($data)
    {
        if (!isset($data)) {
            Log::out("Export::showData() - No data to report.", Zend_Log::DEBUG);
            error_log("Export::showData() - No data to report.");
            echo "<div class='si_message_error'>Export process terminated. No data to report.</div>";
            echo "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";
            return null;
        }

        if ($this->file_name == '' && $this->module == 'payment') {
            $this->file_name = 'payment' . $this->id;
        }

        // @formatter:off
        switch ($this->format) {
            case "print":
                echo ($data);
                break;

            case "pdf":
                return Pdf::generate($data, $this->file_name, $this->destination);

            case "file":
                $invoice    = Invoice::getInvoice($this->id);
                $preference = Preferences::getOne($invoice['preference_id']);

                // xls/doc export no longer uses the export template $template = "export";
                header("Content-type: application/octet-stream");

                // header("Content-type: application/x-msdownload");
                switch ($this->module) {
                    case "statement":
                        header('Content-Disposition: attachment; filename="statement.' .
                               addslashes($this->file_type) . '"');
                        break;

                    case "payment":
                        header('Content-Disposition: attachment; filename="payment' .
                               addslashes($this->id . '.' . $this->file_type) . '"');
                        break;

                    default:
                        header('Content-Disposition: attachment; filename="' .
                               addslashes($preference['pref_inv_heading'] . $this->id . '.' .
                               $this->file_type) . '"');
                        break;
                }

                header("Pragma: no-cache");
                header("Expires: 0");
                echo ($data);
                break;
        }
        // @formatter:on
        return null;
    }

    /**
     * @return null|string
     */
    private function getData()
    {
        global $config, $smarty, $pdoDb, $siUrl;
        Log::out("Export::getData() module:[{$this->module}]", Zend_Log::DEBUG);

        // @formatter:off
        $data = null;
        switch ($this->module) {
            case "statement":
                try {
                    if ($this->do_not_filter_by_date != "yes" && !empty($this->start_date) && !empty($this->end_date)) {
                        $pdoDb->setHavings(Invoice::buildHavings("date_between", array($this->start_date, $this->end_date)));
                    }

                    if ($this->show_only_unpaid == "yes") {
                        $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
                    }

                    if (!empty($this->biller_id)  ) $pdoDb->addSimpleWhere("biller_id"  , $this->biller_id  , "AND");
                    if (!empty($this->customer_id)) $pdoDb->addSimpleWhere("customer_id", $this->customer_id, "AND");

                    $invoices  = Invoice::getAll("date", "desc");
                    $statement = array("total" => 0, "owing" => 0, "paid" => 0);
                    foreach ( $invoices as $row ) {
                        if ($row ['status'] > 0) {
                            $statement ['total'] += $row ['total'];
                            $statement ['owing'] += $row ['owing'];
                            $statement ['paid']  += $row ['paid'];
                        }
                    }

                    $templatePath     = "templates/default/statement/index.tpl";
                    $biller_details   = Biller::getOne($this->biller_id);
                    $billers          = $biller_details;
                    $customer_details = Customer::getOne($this->customer_id);
                    if (empty($this->file_name)) {
                        $pdf_file_name = 'statement';
                        if (!empty($this->biller_id)  ) $pdf_file_name .= '_' . $this->biller_id;
                        if (!empty($this->customer_id)) $pdf_file_name .= '_' . $this->customer_id;
                        if ($this->do_not_filter_by_date != "yes") {
                            if (!empty($this->start_date) && !empty($this->end_date)) {
                                $pdf_file_name .= '_' . $this->start_date;
                                $pdf_file_name .= '_' . $this->end_date;
                            }
                        }
                        $this->file_name = $pdf_file_name;
                    }

                    $smarty->assign('biller_id'            , $this->biller_id);
                    $smarty->assign('biller_details'       , $biller_details);
                    $smarty->assign('billers'              , $billers);
                    $smarty->assign('customer_id'          , $this->customer_id);
                    $smarty->assign('customer_details'     , $customer_details);
                    $smarty->assign('show_only_unpaid'     , $this->show_only_unpaid);
                    $smarty->assign('do_not_filter_by_date', $this->do_not_filter_by_date);
                    $smarty->assign('invoices'             , $invoices);
                    $smarty->assign('start_date'           , $this->start_date);
                    $smarty->assign('end_date'             , $this->end_date);
                    $smarty->assign('statement'            , $statement);
                    $smarty->assign('menu'                 , false);
                    $data = $smarty->fetch($templatePath);
                } catch (\Exception $e) {
                    error_log("Export::getData() - statement - error: " . $e->getMessage());
                }
                break;

            case "payment":
                try {
                    $payment = Payment::getOne($this->id);

                    // Get Invoice preference to link from this screen back to the invoice
                    $invoice = Invoice::getInvoice($payment['ac_inv_id']);
                    $biller  = Biller::getOne($payment['biller_id']);

                    $logo = Util::getLogo($biller);
                    $logo = str_replace(" ", "%20", trim($logo));

                    $customer          = Customer::getOne($payment['customer_id']);
                    $invoiceType       = Invoice::getInvoiceType($invoice['type_id']);
                    $customFieldLabels = CustomFields::getLabels(true);
                    $paymentType       = PaymentType::getOne($payment['ac_payment_type']);
                    $preference        = Preferences::getOne($invoice['preference_id']);

                    $smarty->assign("payment"          , $payment);
                    $smarty->assign("invoice"          , $invoice);
                    $smarty->assign("biller"           , $biller);
                    $smarty->assign("logo"             , $logo);
                    $smarty->assign("customer"         , $customer);
                    $smarty->assign("invoiceType"      , $invoiceType);
                    $smarty->assign("paymentType"      , $paymentType);
                    $smarty->assign("preference"       , $preference);
                    $smarty->assign("customFieldLabels", $customFieldLabels);
                    $smarty->assign('pageActive'       , 'payment');
                    $smarty->assign('active_tab'       , '#money');

                    $css = $siUrl . "templates/invoices/default/style.css";
                    $smarty->assign('css', $css);

                    $templatePath = "templates/default/payments/print.tpl";
                    $data = $smarty->fetch($templatePath);
                } catch (\Exception $e) {
                    error_log("Export::getData() - payment - error: " . $e->getMessage());
                }
                break;

            case "invoice":
                try {
                    if (empty($this->invoice)) {
                        $this->invoice = Invoice::getOne($this->id);
                    }

                    $this->file_name = str_replace(" ", "_", $this->invoice['index_name']);

                    $invoice_number_of_taxes = Invoice::numberOfTaxesForInvoice($this->id);
                    $invoiceItems = Invoice::getInvoiceItems($this->id);

                    if (!isset($this->customer)) $this->customer = Customer::getOne($this->invoice['customer_id']);
                    if (!isset($this->biller)) $this->biller = Biller::getOne($this->invoice['biller_id']);
                    if (!isset($this->preference)) $this->preference = Preferences::getOne($this->invoice['preference_id']);

                    $defaults = SystemDefaults::loadValues();

                    $logo = Util::getLogo($this->biller);
                    $logo = str_replace(" ", "%20", trim($logo));
                    Log::out("Export::getData() - logo[$logo]", Zend_Log::DEBUG);

                    $customFieldLabels = CustomFields::getLabels(true);

                    $past_due_date = (date("Y-m-d", strtotime('-30 days')) . ' 00:00:00');
                    $past_due_amt  = CustomersPastDue::getCustomerPastDue($this->invoice['customer_id'], $this->id, $past_due_date);

                    // Set the template to the default
                    $template = $defaults['template'];

                    $templatePath  = "templates/invoices/{$template}/template.tpl";
                    $template_path = "templates/invoices/{$template}";
                    $css           = $siUrl . "templates/invoices/{$template}/style.css";

                    $pageActive = "invoices";
                    $smarty->assign('pageActive', $pageActive);

                    $orig_locale = $this->assignTemplateLanguage($this->preference);

                    $smarty->assign('biller'                 , $this->biller);
                    $smarty->assign('customer'               , $this->customer);
                    $smarty->assign('invoice'                , $this->invoice);
                    $smarty->assign('invoice_number_of_taxes', $invoice_number_of_taxes);
                    $smarty->assign('preference'             , $this->preference);
                    $smarty->assign('logo'                   , $logo);
                    $smarty->assign('template'               , $template);
                    $smarty->assign('invoiceItems'           , $invoiceItems);
                    $smarty->assign('template_path'          , $template_path);
                    $smarty->assign('css'                    , $css);
                    $smarty->assign('customFieldLabels'      , $customFieldLabels);
                    $smarty->assign('past_due_amt'           , $past_due_amt);

                    // Plugins specifically associated with your invoice template.
                    $template_plugins_dir = "templates/invoices/${template}/plugins/";
                    if (is_dir($template_plugins_dir)) {
                        $plugins_dirs = $smarty->getPluginsDir();
                        if (!is_array($plugins_dirs)) $plugins_dirs = array($plugins_dirs);
                        $plugins_dirs[] = $template_plugins_dir;
                        $smarty->setPluginsDir($plugins_dirs);
                    }
                    Log::out("Export::getData() - templatePath[$templatePath]", Zend_Log::DEBUG);

                    $data = $smarty->fetch($templatePath);

                    // Restore configured locale
                    if (!empty($orig_locale)) {
                        $config->local->locale = $orig_locale;
                    }
                } catch (\Exception $e) {
                    error_log("Export::getData() - invoice - error: " . $e->getMessage());
                }
                break;

            default:
                error_log("Export::getData() - Undefined module[{$this->module}]");
                break;
        }
        // @formatter:on

        return $data;
    }

    /**
     * Execute the request by getting the data and the showing it.
     * @return string/null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     */
    public function execute()
    {
        return $this->showData($this->getData());
    }

    /**
     * Assign the language and set the locale from the preference
     * @param $preference
     * @return mixed
     */
    private function assignTemplateLanguage($preference)
    {
        global $config;

        // get and assign the language file from the preference table
        $pref_language = $preference['language'];
        if (!empty($pref_language)) {
            $LANG = getLanguageArray($pref_language);
            if (isset($LANG) && is_array($LANG) && count($LANG) > 0) {
                global $smarty;
                $smarty->assign('LANG', $LANG);
            }
        }

        // Override config's locale with the one assigned from the preference table
        $orig_locale = $config->local->locale;
        $pref_locale = $preference['locale'];
        if (isset($pref_language) && strlen($pref_locale) > 4) {
            $config->local->locale = $pref_locale;
        }
        return $orig_locale;
    }

    /**
     * @return null
     */
    public function getBiller()
    {
        return $this->biller;
    }

    /**
     * @param null $biller
     */
    public function setBiller($biller): void
    {
        $this->biller = $biller;
    }

    /**
     * @return null
     */
    public function getBillerId()
    {
        return $this->biller_id;
    }

    /**
     * @param null $biller_id
     */
    public function setBillerId($biller_id): void
    {
        $this->biller_id = $biller_id;
    }

    /**
     * @return null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param null $customer
     */
    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return null
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @param null $customer_id
     */
    public function setCustomerId($customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getDoNotFilterByDate(): string
    {
        return $this->do_not_filter_by_date;
    }

    /**
     * @param string $do_not_filter_by_date
     */
    public function setDoNotFilterByDate(string $do_not_filter_by_date): void
    {
        $this->do_not_filter_by_date = $do_not_filter_by_date;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    /**
     * @param string $end_date
     */
    public function setEndDate(string $end_date): void
    {
        $this->end_date = $end_date;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->file_name;
    }

    /**
     * @param string $file_name
     */
    public function setFileName(string $file_name): void
    {
        $this->file_name = $file_name;
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->file_type;
    }

    /**
     * @param string $file_type
     */
    public function setFileType(string $file_type): void
    {
        $this->file_type = $file_type;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param null $invoice
     */
    public function setInvoice($invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @param string $module
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * @return null
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param null $preference
     */
    public function setPreference($preference): void
    {
        $this->preference = $preference;
    }

    /**
     * @return string
     */
    public function getShowOnlyUnpaid(): string
    {
        return $this->show_only_unpaid;
    }

    /**
     * @param string $show_only_unpaid
     */
    public function setShowOnlyUnpaid(string $show_only_unpaid): void
    {
        $this->show_only_unpaid = $show_only_unpaid;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->start_date;
    }

    /**
     * @param string $start_date
     */
    public function setStartDate(string $start_date): void
    {
        $this->start_date = $start_date;
    }

}
