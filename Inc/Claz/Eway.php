<?php

namespace Inc\Claz;

use Encryption;
use EwayLib;
use Exception;

/**
 * Class Eway
 * @package Inc\Claz
 */
class Eway
{
    public array $biller;
    public array $invoice;
    public array $customer;
    public array $preference;
    /**
     * @var mixed
     */
    public $message;

    public function preCheck(): string
    {
        //set customer,biller and preference if not defined
        if (empty($this->customer)) {
            $this->customer = Customer::getOne($this->invoice['customer_id']);
        }

        if (empty($this->biller)) {
            $this->biller = Biller::getOne($this->invoice['biller_id']);
        }

        if (empty($this->preference)) {
            $this->preference = Preferences::getOne($this->invoice['preference_id']);
        }

        if ($this->invoice['owing'] > 0 &&
            $this->biller['eway_customer_id'] != '' &&
            $this->customer['credit_card_number'] != '' &&
            in_array("eway_merchant_xml", explode(",", $this->preference['include_online_payment']))) {
            return 'true';
        }

        return 'false';
    }

    /**
     * @return string
     * @throws Exception
     */
    public function payment(): string
    {
        global $config, $LANG;

        //set customer,biller and preference if not defined
        if (empty($this->customer)) {
            $this->customer = Customer::getOne($this->invoice['customer_id']);
        }

        if (empty($this->biller)) {
            $this->biller = Biller::getOne($this->invoice['biller_id']);
        }

        if (empty($this->preference)) {
            $this->preference = Preferences::getOne($this->invoice['preference_id']);
        }

        $eway = new EwayLib($this->biller['eway_customer_id'], 'REAL_TIME', false);

        //Eway only accepts amount in cents - so times 100
        $value = $this->invoice['total'] * 100;
        $ewayInvoiceTotal = Util::htmlSafe(trim($value));
        Log::out("eway total: " . $ewayInvoiceTotal, Log::INFO);

        try {
            $key = $config['encryptionDefaultKey'];
            $enc = new Encryption();
            $creditCardNumber = $enc->decrypt($key, $this->customer['credit_card_number']);
        } catch (Exception $exp) {
            return 'false';
        }

        // @formatter:off
        $eway->setTransactionData("TotalAmount"               , $ewayInvoiceTotal); //mandatory field
        $eway->setTransactionData("CustomerFirstName"         , $this->customer['name']);
        $eway->setTransactionData("CustomerLastName"          , "");
        $eway->setTransactionData("CustomerAddress"           , "");
        $eway->setTransactionData("CustomerPostcode"          , "");
        $eway->setTransactionData("CustomerInvoiceDescription", "");
        $eway->setTransactionData("CustomerEmail"             , $this->customer['email']);
        $eway->setTransactionData("CustomerInvoiceRef"        , $this->invoice['index_name']);
        $eway->setTransactionData("CardHoldersName"           , $this->customer['credit_card_holder_name']); //mandatory field
        $eway->setTransactionData("CardNumber"                , $creditCardNumber); //mandatory field
        $eway->setTransactionData("CardExpiryMonth"           , $this->customer['credit_card_expiry_month']); //mandatory field
        $eway->setTransactionData("CardExpiryYear"            , $this->customer['credit_card_expiry_year']); //mandatory field
        $eway->setTransactionData("Option1"                   , "");
        $eway->setTransactionData("Option2"                   , "");
        $eway->setTransactionData("Option3"                   , "");
        $eway->setTransactionData("TrxnNumber"                , $this->invoice['id']);
        // @formatter:on

        //special preferences for php Curl
        //pass a long set to zero value stops curl from verifying peer's certificate
        $eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0);
        $ewayResponseFields = $eway->doPayment();
        $this->message = $ewayResponseFields;
        $message = "";
        if ($ewayResponseFields['EWAYTRXNSTATUS'] == "False") {
            Log::out("{$LANG['transactionUc']} {$LANG['errorUc']}: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Log::INFO);
            foreach ($ewayResponseFields as $key => $value) {
                $message .= "\n<br>\${$LANG['ewayResponseFields']}[\"$key\"] = $value";
            }
            Log::out("{$LANG['eway']} {$LANG['message']}: " . $message . "<br>\n", Log::INFO);
            return 'false';
        }

        if ($ewayResponseFields["EWAYTRXNSTATUS"] == "True") {
            Log::out("{$LANG['transactionUc']} {$LANG['successUc']}: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n", Log::INFO);
            foreach ($ewayResponseFields as $key => $value) {
                $message .= "\n<br>\${$LANG['ewayResponseFields']}[\"$key\"] = $value";
            }
            Log::out("{$LANG['eway']} {$LANG['message']}: " . $message . "<br>\n", Log::INFO);

            // @formatter:off
            Payment::insert([
                "ac_inv_id"         => $this->invoice['id'],
                "ac_amount"         => $this->invoice['total'],
                "ac_notes"          => $message,
                "ac_date"           => date('Y-m-d'),
                "online_payment_id" => $ewayResponseFields['EWAYTRXNNUMBER'],
                "domain_id"         => DomainId::get(),
                "ac_payment_type"   => PaymentType::selectOrInsertWhere("Eway")
            ]);

            return 'true';
        }

        return 'false';
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

}
