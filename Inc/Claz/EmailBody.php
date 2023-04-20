<?php

namespace Inc\Claz;

/**
 * Class EmailBody
 * @package Inc\Claz
 */
class EmailBody
{
    public string $emailType;
    public string $customerName;
    public string $invoiceName;
    public string $billerName;

    public function create(): string
    {
        /** @noinspection PhpSwitchCanBeReplacedWithMatchExpressionInspection */
        switch ($this->emailType) {
            case "cron_payment":
                $emailBody = "$this->customerName, A PDF copy of your payment receipt is attached.<br /><br />Thank you for using our service,<br />$this->billerName";
                break;

            case "cron_invoice_reprint":
            case "cron_invoice":
            default:
                $emailBody = "$this->customerName, A PDF copy of your invoice is attached.<br /><br />Thank you for using our service,<br />$this->billerName";
                break;
        }

        return $emailBody;
    }
}
