<?php

namespace Inc\Claz;

use Exception;
use Mpdf\Output\Destination;

/**
 * Class Export
 * @package Inc\Claz
 */
class Export
{
    private const MODULES = "/^(invoice|payment|reports|statement)$/";
    private const DESTINATIONS = "/^(" . Destination::FILE . "|" . Destination::DOWNLOAD . "|" . Destination::STRING_RETURN . "|" . Destination::INLINE . ")$/";

    private array $biller;
    private ?int $billerId;
    private array $customer;
    private ?int $customerId;
    private string $destination;
    private string $displayDetail;
    private string $filterByDateRange;
    private string $endDate;
    private string $fileName;
    private string $fileType;
    private string $format;
    private ?int $invoiceId;
    private array $invoices;
    private bool $landscape;
    private string $module;
    private array $params;
    private ?int $paymentId;
    private array $payments;
    private array $preference;
    private ?int $preferenceId;
    private string $showOnlyUnpaid;
    private string $startDate;

    /**
     * Export constructor.
     * @param string $destination Set to Destination::DOWNLOAD for file or
     *      Destination::STRING for email attachment.
     */
    public function __construct(string $destination)
    {
        if (preg_match(self::DESTINATIONS, $destination) != 1) {
            $str = "Export::__construct() - Invalid destination[{$destination}] specified.";
            error_log($str);
            exit($str);
        }

        // @formatter:off
        $this->biller            = [];
        $this->billerId          = null;
        $this->customer          = [];
        $this->customerId        = null;
        $this->destination       = $destination;
        $this->displayDetail     = "no";
        $this->endDate           = "";
        $this->fileName          = "";
        $this->fileType          = "";
        $this->filterByDateRange = "yes";
        $this->format            = "";
        $this->invoiceId         = null;
        $this->invoices          = [];
        $this->landscape         = false;
        $this->module            = "";
        $this->params            = [];
        $this->paymentId         = null;
        $this->payments          = [];
        $this->preference        = [];
        $this->preferenceId      = null;
        $this->showOnlyUnpaid    = "no";
        $this->startDate         = "";
        // @formatter:on
    }

    /**
     * @param string $data
     * @return string|null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     * @throws PdoDbException
     */
    private function showData(string $data): ?string
    {
        global $LANG;

        if (empty($data)) {
            Log::out("Export::showData() - No data to report.");
            error_log("Export::showData() - No data to report.");
            echo "<div class='si_message_error'>" .
                     "{$LANG['exportUc']} {$LANG['process']} {$LANG['terminated']}. " .
                     "{$LANG['noUc']} {$LANG['data']} {$LANG['to']} {$LANG['report']}." .
                 "</div>";
            echo "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";
            return null;
        }

        Log::out("Export::showData() - fileName[{$this->fileName}] module[{$this->module}]");
        if (empty($this->fileName) && $this->module == 'payment') {
            $this->fileName = 'payment' . $this->paymentId;
        }

        Log::out("Export::showData() - format[{$this->format}] fileType[{$this->fileType}]");
        // formatter:off
        switch ($this->format) {
            case "pdf":
                return Pdf::generate($data, $this->fileName, $this->destination, $this->landscape);

            case "print":
                echo $data;
                break;

            case "file":
                if ($this->module != "reports" && !empty($this->invoiceId)) {
                    $invoice = Invoice::getOne($this->invoiceId);
                    $preference = Preferences::getOne($invoice['preference_id']);
                }

                // xls/doc export no longer uses the export template $template = "export";
                header("Content-type: application/octet-stream");

                // header("Content-type: application/x-msdownload");
                switch ($this->module) {
                    case "invoice":
                        /** @noinspection PhpUndefinedVariableInspection */
                        $fileName = addslashes("{$preference['pref_inv_heading']}_{$invoice['index_id']}.{$this->fileType}");
                        break;

                    case "payment":
                        $fileName = addslashes("payment_{$this->paymentId}.{$this->fileType}");
                        break;

                    case "reports":
                        $fileName = addslashes("{$this->fileName}.{$this->fileType}");
                        break;

                    case "statement":
                        $fileName = addslashes("statement.{$this->fileType}");
                        break;

                    default:
                        $str = "Export::showData() - Unexpected module[{$this->module}]";
                        error_log($str);
                        exit($str);
                }

                Log::out("Export::showData() - attachment filename[{$fileName}]");
                header("Content-Disposition: attachment; filename={$fileName}");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $data;
                break;


            default:
                $str = "Export::showData() - Unexpected module[{$this->module}]";
                error_log($str);
                exit($str);
        }
        // formatter:on

        return null;
    }

    /**
     * Get the data to report for the fileName to report.
     * @return string
     */
    private function getData(): string
    {
        global $config, $smarty, $pdoDb, $siUrl;

        Log::out("Export::getData() fileName:[{$this->fileName}]");

        // @formatter:off
        $data = "";
        /** @noinspection PhpSwitchCaseWithoutDefaultBranchInspection */
        switch ($this->module) {
            case "invoice":
                try {
                    $invoice = Invoice::getOne($this->invoiceId);

                    $this->fileName = str_replace(" ", "_", $invoice['index_name']);
                    Log::out("Export::getData() - file_name[$this->fileName]");

                    $invoiceNumberOfTaxes = Invoice::numberOfTaxesForInvoice($this->invoiceId);
                    $invoiceItems = Invoice::getInvoiceItems($this->invoiceId);

                    if (empty($this->customer)) {
                        $this->customer = Customer::getOne($invoice['customer_id']);
                    }
                    if (isset($this->billerId)) {
                        $biller = Biller::getOne($this->billerId);
                    } else {
                        $biller = Biller::getOne($invoice['biller_id']);
                    }
                    if (isset($this->preferenceId)) {
                        $preference = Preferences::getOne($this->preferenceId);
                    } else {
                        $preference = Preferences::getOne($invoice['preference_id']);
                    }

                    $defaults = SystemDefaults::loadValues();

                    $logo = Util::getLogo($biller);
                    $logo = str_replace(" ", "%20", trim($logo));
                    Log::out("Export::getData() - logo[$logo]");

                    $customFieldLabels = CustomFields::getLabels(true);

                    $pastDueDate = date("Y-m-d", strtotime('-30 days')) . ' 00:00:00';
                    $pastDueAmt  = CustomersPastDue::getCustomerPastDue($invoice['customer_id'], $pastDueDate, $this->invoiceId);

                    // Set the template to the default
                    $template = $defaults['template'];

                    $templateDir = "templates/invoices/{$template}";
                    $css = $siUrl . "templates/invoices/{$template}/style.css";

                    $pageActive = "invoices";
                    $smarty->assign('pageActive', $pageActive);

                    $origLocale = $this->assignTemplateLanguage($this->preference);

                    $smarty->assign('biller'              , $biller);
                    $smarty->assign('customer'            , $this->customer);
                    $smarty->assign('invoice'             , $invoice);
                    $smarty->assign('invoiceNumberOfTaxes', $invoiceNumberOfTaxes);
                    $smarty->assign('preference'          , $preference);
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

                    $data = $smarty->fetch("templates/invoices/{$template}/template.tpl");

                    // Restore configured locale
                    if (!empty($origLocale)) {
                        $config['localLocale'] = $origLocale;
                    }
                } catch (Exception $exp) {
                    error_log("Export::getData() - invoice - error: " . $exp->getMessage());
                }
                break;

            case "payment":
                try {
                    $payment = Payment::getOne($this->paymentId);

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
                    $smarty->assign('activeTab'       , '#money');

                    $css = $siUrl . "templates/invoices/default/style.css";
                    $smarty->assign('css', $css);

                    $data = $smarty->fetch("templates/default/payments/print.tpl");
                } catch (Exception $exp) {
                    error_log("Export::getData() - payment - error: " . $exp->getMessage());
                }
                break;

            case "statement":
                try {
                    if ($this->filterByDateRange == "yes") {
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

                    $billerDetails   = Biller::getOne($this->billerId);
                    $billers          = $billerDetails;
                    $customerDetails = Customer::getOne($this->customerId);
                    if (empty($this->fileName)) {
                        $pdfFileName = 'statement';
                        if (!empty($this->billerId)  ) {
                            $pdfFileName .= '_' . $this->billerId;
                        }
                        if (!empty($this->customerId)) {
                            $pdfFileName .= '_' . $this->customerId;
                        }
                        if ($this->filterByDateRange == "yes") {
                            $pdfFileName .= '_' . $this->startDate;
                            $pdfFileName .= '_' . $this->endDate;
                        }
                        $this->fileName = $pdfFileName;
                    }

                    $smarty->assign('billerId'         , $this->billerId);
                    $smarty->assign('billerDetails'    , $billerDetails);
                    $smarty->assign('billers'          , $billers);
                    $smarty->assign('customerId'       , $this->customerId);
                    $smarty->assign('customerDetails'  , $customerDetails);
                    $smarty->assign('showOnlyUnpaid'   , $this->showOnlyUnpaid);
                    $smarty->assign('filterByDateRange', $this->filterByDateRange);
                    $smarty->assign('invoices'         , $invoices);
                    $smarty->assign('startDate'        , $this->startDate);
                    $smarty->assign('endDate'          , $this->endDate);
                    $smarty->assign('statement'        , $statement);
                    $smarty->assign('menu'             , false);
                    $data = $smarty->fetch("templates/default/statement/index.tpl");
                } catch (Exception $exp) {
                    error_log("Export::getData() - statement - error: " . $exp->getMessage());
                }
                break;
        }
        // @formatter:on

        return $data;
    }

    /**
     * Execute the request by getting the data and the showing it.
     * @return string|null String returned if PDF for Destination::STRING_RETURN, otherwise null;
     * @throws PdoDbException
     */
    public function execute(): ?string
    {
        $module = empty($_GET['module']) ? "" : $_GET['module'];
        if ($module == 'reports') {
            return $this->showData($this->getReportData());
        }
        return $this->showData($this->getData());
    }

    private function getReportData(): string
    {
        global $path, $smarty;

        $this->fileName = empty($_GET['fileName']) ? "" : $_GET['fileName'];
        $this->fileType = empty($_GET['fileType']) ? "" : $_GET['fileType'];
        if (empty($this->fileName)) {
            $str = "Export::getReportData() - Undefined fileName[{$this->fileName}].";
            error_log($str);
            exit($str);
        }

        Log::out("Export::getReportData() - fileName[{$this->fileName}] fileType[{$this->fileType}]");
        foreach($_GET as $key => $value) {
            Log::out("Export::getReportData() - \$_GET info: key[{$key}] value[{$value}]");
            $smarty->assign($key, $value);
        }

        $smarty->assign('menu', true);

        $template = "{$path}{$this->fileName}Body.tpl";

        try {
            $fetched = $smarty->fetch($template);
        } catch (Exception $exp) {
            $str = "Export::getReportData() - Unexpected smarty error: {$exp->getMessage()}";
            error_log($str);
            exit($str);
        }

        return $fetched;
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
        return $this->filterByDateRange;
    }

    public function setFilterByDateRange(string $filterByDateRange): void
    {
        $this->filterByDateRange = $filterByDateRange;
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
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileType(): string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): void
    {
        $this->fileType = $fileType;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function geInvoices(): array
    {
        return $this->invoices;
    }

    public function setInvoices(array $invoices): void
    {
        $this->invoices = $invoices;
    }

    public function geInvoiceId(): int
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getLandscape(): bool
    {
        return $this->landscape;
    }

    public function setLandscape(bool $landscape): void
    {
        $this->landscape = $landscape;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function setModule(string $module): void
    {
        if (!preg_match(self::MODULES, $module)) {
            $str = "Export::setModule() - Undefined module[{$module}]";
            error_log($str);
            exit($str);
        }
        $this->module = $module;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getPayments(): array
    {
        return $this->payments;
    }

    public function setPayments(array $payments): void
    {
        $this->payments = $payments;
    }

    public function gePaymentId(): int
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getPreference(): array
    {
        return $this->preference;
    }

    public function setPreference(array $preference): void
    {
        $this->preference = $preference;
    }

    public function getPreferenceId(): int
    {
        return $this->preferenceId;
    }

    public function setPreferenceId(int $preferenceId): void
    {
        $this->preferenceId = $preferenceId;
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
