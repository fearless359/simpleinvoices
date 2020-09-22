<?php

namespace Inc\Claz;

use Exception;

/**
 * Class Export
 * @package Inc\Claz
 */
class Export
{
    private array $biller;
    private ?int $billerId;
    private array $customer;
    private ?int $customerId;
    private string $destination;
    private string $displayDetail;
    private string $filterByDate;
    private string $endDate;
    private string $reportFileName;
    private string $reportFileType;
    private string $reportFormat;
    private array $invoice;
    private array $preference;
    private int $recId;
    private string $showOnlyUnpaid;
    private string $startDate;

    /**
     * Export constructor.
     * @param string $destination Set to Destination::DOWNLOAD for file or
     *      Destination::STRING for email attachment.
     */
    public function __construct(string $destination)
    {
        // @formatter:off
        $this->biller         = [];
        $this->billerId       = 0;
        $this->customer       = [];
        $this->customerId     = 0;
        $this->destination    = $destination;
        $this->displayDetail  = "no";
        $this->filterByDate   = "yes";
        $this->endDate        = "";
        $this->reportFileName = "";
        $this->reportFileType = "";
        $this->reportFormat   = "";
        $this->recId          = 0;
        $this->invoice        = [];
        $this->preference     = [];
        $this->showOnlyUnpaid = "no";
        $this->startDate      = "";
        // @formatter:on
    }

    /**
     * @param mixed $data
     * @return string|null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     * @throws PdoDbException
     */
    private function showData($data)
    {
        global $LANG;

        if (!isset($data)) {
            Log::out("Export::showData() - No data to report.");
            error_log("Export::showData() - No data to report.");
            echo "<div class='si_message_error'>" .
                     "{$LANG['exportUc']} {$LANG['process']} {$LANG['terminated']}. " .
                     "{$LANG['noUc']} {$LANG['data']} {$LANG['to']} {$LANG['report']}." .
                 "</div>";
            echo "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";
            return null;
        }

        Log::out("Export::showData() - reportFileType[{$this->reportFileType}]");
        if (empty($this->reportFileName) && $this->reportFileName == 'payment') {
            $this->reportFileName = 'payment' . $this->recId;
        }

        Log::out("Export::showData() - reportFormat[{$this->reportFormat}]");
        switch ($this->reportFormat) {
            case "print":
                echo $data;
                break;

            case "pdf":
                return Pdf::generate($data, $this->reportFileName, $this->destination);

            case "file":
            default:
                if (!empty($this->recId)) {
                    $invoice = Invoice::getOne($this->recId);
                    $preference = Preferences::getOne($invoice['preference_id']);
                }

                // xls/doc export no longer uses the export template $template = "export";
                header("Content-type: application/octet-stream");

                // header("Content-type: application/x-msdownload");
                switch ($this->reportFileName) {
                    case "statement":
                        header('Content-Disposition: attachment; fileName="statement.' .
                            addslashes($this->reportFileType) . '"');
                        break;

                    case "payment":
                        header('Content-Disposition: attachment; fileName="payment' .
                            addslashes($this->recId . '.' . $this->reportFileType) . '"');
                        break;

                    default:
                        /** @noinspection PhpUndefinedVariableInspection */
                        $reportFileName = addslashes($preference['pref_inv_heading'] . '_' . $invoice['index_id'] . '.' . $this->reportFileType);
                        header('Content-Disposition: attachment; fileName="' . $reportFileName . '"');
                        break;
                }

                header("Pragma: no-cache");
                header("Expires: 0");
                echo $data;
                break;
        }

        return null;
    }

    /**
     * Get the data to report for the reportFileName to report.
     * @return string
     */
    private function getData(): string
    {
        global $config, $smarty, $pdoDb, $siUrl;

        Log::out("Export::getData() reportFileName:[{$this->reportFileName}]");

        // @formatter:off
        $data = "";
        switch ($this->reportFileName) {
            case "statement":
                try {
                    if ($this->filterByDate == "yes") {
                        $pdoDb->setHavings(Invoice::buildHavings("date_between", [$this->startDate, $this->endDate]));
                    }

                    if ($this->showOnlyUnpaid == "yes") {
                        $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
                    }

                    if (!empty($this->billerId)  ) {
                        $pdoDb->addSimpleWhere("biller_id"  , $this->billerId  , "AND");
                    }

                    if (!empty($this->customerId)) {
                        $pdoDb->addSimpleWhere("customer_id", $this->customerId, "AND");
                    }

                    $invoices  = Invoice::getAll("date", "desc");
                    $statement = ["total" => 0, "owing" => 0, "paid" => 0];
                    foreach ( $invoices as $row ) {
                        if ($row ['status'] > 0) {
                            $statement ['total'] += $row ['total'];
                            $statement ['owing'] += $row ['owing'];
                            $statement ['paid']  += $row ['paid'];
                        }
                    }

                    $templatePath     = "templates/default/statement/index.tpl";
                    $billerDetails   = Biller::getOne($this->billerId);
                    $billers          = $billerDetails;
                    $customerDetails = Customer::getOne($this->customerId);
                    if (empty($this->reportFileName)) {
                        $pdfFileName = 'statement';
                        if (!empty($this->billerId)  ) {
                            $pdfFileName .= '_' . $this->billerId;
                        }
                        if (!empty($this->customerId)) {
                            $pdfFileName .= '_' . $this->customerId;
                        }
                        if ($this->filterByDate == "yes") {
                            $pdfFileName .= '_' . $this->startDate;
                            $pdfFileName .= '_' . $this->endDate;
                        }
                        $this->reportFileName = $pdfFileName;
                    }

                    $smarty->assign('billerId'       , $this->billerId);
                    $smarty->assign('billerDetails'  , $billerDetails);
                    $smarty->assign('billers'        , $billers);
                    $smarty->assign('customerId'     , $this->customerId);
                    $smarty->assign('customerDetails', $customerDetails);
                    $smarty->assign('showOnlyUnpaid' , $this->showOnlyUnpaid);
                    $smarty->assign('filterByDate'   , $this->filterByDate);
                    $smarty->assign('invoices'       , $invoices);
                    $smarty->assign('startDate'      , $this->startDate);
                    $smarty->assign('endDate'        , $this->endDate);
                    $smarty->assign('statement'      , $statement);
                    $smarty->assign('menu'           , false);
                    $data = $smarty->fetch($templatePath);
                } catch (Exception $exp) {
                    error_log("Export::getData() - statement - error: " . $exp->getMessage());
                }
                break;

            case "payment":
                try {
                    $payment = Payment::getOne($this->recId);

                    // Get Invoice preference to link from this screen back to the invoice
                    $invoice = Invoice::getOne($payment['ac_inv_id']);
                    $biller  = Biller::getOne($payment['billerId']);

                    $logo = Util::getLogo($biller);
                    $logo = str_replace(" ", "%20", trim($logo));

                    $customer          = Customer::getOne($payment['customerId']);
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
                } catch (Exception $exp) {
                    error_log("Export::getData() - payment - error: " . $exp->getMessage());
                }
                break;

            case "invoice":
                try {
                    if (empty($this->invoice)) {
                        $this->invoice = Invoice::getOne($this->recId);
                    }

                    $this->reportFileName = str_replace(" ", "_", $this->invoice['index_name']);
                    Log::out("Export::getData() - file_name[$this->reportFileName]");

                    $invoiceNumberOfTaxes = Invoice::numberOfTaxesForInvoice($this->recId);
                    $invoiceItems = Invoice::getInvoiceItems($this->recId);

                    if (empty($this->customer)) {
                        $this->customer = Customer::getOne($this->invoice['customer_id']);
                    }
                    if (empty($this->biller)) {
                        $this->biller = Biller::getOne($this->invoice['biller_id']);
                    }
                    if (empty($this->preference)) {
                        $this->preference = Preferences::getOne($this->invoice['preference_id']);
                    }

                    $defaults = SystemDefaults::loadValues();

                    $logo = Util::getLogo($this->biller);
                    $logo = str_replace(" ", "%20", trim($logo));
                    Log::out("Export::getData() - logo[$logo]");

                    $customFieldLabels = CustomFields::getLabels(true);

                    $pastDueDate = date("Y-m-d", strtotime('-30 days')) . ' 00:00:00';
                    $pastDueAmt  = CustomersPastDue::getCustomerPastDue($this->invoice['customer_id'], $pastDueDate, $this->recId);

                    // Set the template to the default
                    $template = $defaults['template'];

                    $templatePath  = "templates/invoices/{$template}/template.tpl";
                    $templateDir = "templates/invoices/{$template}";
                    $css           = $siUrl . "templates/invoices/{$template}/style.css";

                    $pageActive = "invoices";
                    $smarty->assign('pageActive', $pageActive);

                    $origLocale = $this->assignTemplateLanguage($this->preference);

                    $smarty->assign('biller'              , $this->biller);
                    $smarty->assign('customer'            , $this->customer);
                    $smarty->assign('invoice'             , $this->invoice);
                    $smarty->assign('invoiceNumberOfTaxes', $invoiceNumberOfTaxes);
                    $smarty->assign('preference'          , $this->preference);
                    $smarty->assign('logo'                , $logo);
                    $smarty->assign('template'            , $template);
                    $smarty->assign('invoiceItems'        , $invoiceItems);
                    $smarty->assign('template_path'       , $templateDir);
                    $smarty->assign('css'                 , $css);
                    $smarty->assign('customFieldLabels'   , $customFieldLabels);
                    $smarty->assign('pastDueAmt'          , $pastDueAmt);

                    // Plugins specifically associated with your invoice template.
                    $templatePluginsDir = "templates/invoices/${template}/plugins/";
                    if (is_dir($templatePluginsDir)) {
                        $pluginsDirs = $smarty->getPluginsDir();
                        if (!is_array($pluginsDirs)) {
                            $pluginsDirs = [$pluginsDirs];
                        }
                        $pluginsDirs[] = $templatePluginsDir;
                        $smarty->setPluginsDir($pluginsDirs);
                    }
                    Log::out("Export::getData() - templatePath[$templatePath]");

                    $data = $smarty->fetch($templatePath);

                    // Restore configured locale
                    if (!empty($origLocale)) {
                        $config['localLocale'] = $origLocale;
                    }
                } catch (Exception $exp) {
                    error_log("Export::getData() - invoice - error: " . $exp->getMessage());
                }
                break;

            default:
                error_log("Export::getData() - Undefined reportFileName[{$this->reportFileName}]");
                break;
        }
        // @formatter:on

        return $data;
    }

    /**
     * Execute the request by getting the data and the showing it.
     * @param string $type Type of action being performed. Defaults to no type which is a standard
     *      export type.
     * @return string|null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     * @throws PdoDbException
     */
    public function execute(string $type = ""): ?string
    {
        switch($type)
        {
            case "report":
                return $this->showReport();
            default:
                break;
        }

        return $this->showData($this->getData());
    }

    private function showReport()
    {
        $data = "";  // holder while developing this code.
        return $data;
    }

    /**
     * Assign the language and set the locale from the preference
     * @param array $preference
     * @return string
     */
    private function assignTemplateLanguage(array $preference): string
    {
        global $config;

        // get and assign the language file from the preference table
        $prefLanguage = $preference['language'];
        if (!empty($prefLanguage)) {
            $LANG = getLanguageArray($prefLanguage);
            if (isset($LANG) && is_array($LANG) && count($LANG) > 0) {
                global $smarty;
                $smarty->assign('LANG', $LANG);
            }
        }

        // Override config's locale with the one assigned from the preference table
        $origLocale = $config['localLocale'];
        $prefLocale = $preference['locale'];
        if (isset($prefLanguage) && strlen($prefLocale) > 4) {
            $config['localLocale'] = $prefLocale;
        }
        return $origLocale;
    }

    /**
     * @return null
     */
    public function getBiller()
    {
        return $this->biller;
    }

    public function setBiller(array $biller): void
    {
        $this->biller = $biller;
    }

    public function getBillerId(): int
    {
        return $this->billerId;
    }

    public function setBillerId(?int $billerId): void
    {
        $this->billerId = $billerId;
    }

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $customer): void
    {
        $this->customer = $customer;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    public function getDisplayDetail(): string
    {
        return $this->displayDetail;
    }

    public function setDisplayDetail(string $displayDetail): void
    {
        $this->displayDetail = $displayDetail;
    }

    public function getFilterByDate(): string
    {
        return $this->filterByDate;
    }

    public function setFilterByDate(string $filterByDate): void
    {
        $this->filterByDate = $filterByDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getFileName(): string
    {
        return $this->reportFileName;
    }

    public function setFileName(string $reportFileName): void
    {
        $this->reportFileName = $reportFileName;
    }

    public function getFileType(): string
    {
        return $this->reportFileType;
    }

    public function setFileType(string $reportFileType): void
    {
        $this->reportFileType = $reportFileType;
    }

    public function getFormat(): string
    {
        return $this->reportFormat;
    }

    public function setFormat(string $reportFormat): void
    {
        $this->reportFormat = $reportFormat;
    }

    public function getInvoice(): array
    {
        return $this->invoice;
    }

    public function setInvoice(array $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function getPreference(): array
    {
        return $this->preference;
    }

    public function setPreference(array $preference): void
    {
        $this->preference = $preference;
    }

    public function geRecId(): int
    {
        return $this->recId;
    }

    public function setRecId(int $recId): void
    {
        $this->recId = $recId;
    }

    public function getShowOnlyUnpaid(): string
    {
        return $this->showOnlyUnpaid;
    }

    public function setShowOnlyUnpaid(string $showOnlyUnpaid): void
    {
        $this->showOnlyUnpaid = $showOnlyUnpaid;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

}
