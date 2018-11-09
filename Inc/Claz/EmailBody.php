<?php
namespace Inc\Claz;

/**
 * Class EmailBody
 * @package Inc\Claz
 */
class EmailBody
{
    public $email_type;
    public $customer_name;
    public $invoice_name;
    public $biller_name;

    /**
     * @return string
     */
    public function create() {
        switch ($this->email_type) {
            case "cron_payment":
                $email_body = "{$this->customer_name}, A PDF copy of your payment receipt is attached.<br /><br />Thank you for using our service,<br />{$this->biller_name}";
                break;

            case "cron_invoice_reprint":
                $email_body = "{$this->customer_name}, A PDF copy of your invoice is attached.<br /><br />Thank you for using our service,<br />{$this->biller_name}";
                break;

            case "cron_invoice":
            default:
                $email_body = "{$this->customer_name}, A PDF copy of your invoice is attached.<br /><br />Thank you for using our service,<br />{$this->biller_name}";
                break;
        }

        return $email_body;
    }
}
