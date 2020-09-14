<?php /** @noinspection PhpConstantNamingConventionInspection */

use Inc\Claz\Util;

$ewayCustomerID = "87654321";    // Set this to your eWAY Customer ID
$ewayPaymentMethod = REAL_TIME;  // Set this to the payment gateway you would like to use (REAL_TIME, REAL_TIME_CVN or GEO_IP_ANTI_FRAUD)
$ewayUseLive = false; // Set this to true to use the live gateway

//define default values for eway
define('EWAY_DEFAULT_CUSTOMER_ID','87654321');
define('EWAY_DEFAULT_PAYMENT_METHOD', REAL_TIME); // possible values are: REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD
define('EWAY_DEFAULT_LIVE_GATEWAY', false); //<false> sets to testing mode, <true> to live mode

//define script constants
define('REAL_TIME', 'REAL-TIME');
define('REAL_TIME_CVN', 'REAL-TIME-CVN');
define('GEO_IP_ANTI_FRAUD', 'GEO-IP-ANTI-FRAUD');

//define URLs for payment gateway
define('EWAY_PAYMENT_LIVE_REAL_TIME', 'https://www.eway.com.au/gateway/xmlpayment.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/xmltest/testpage.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp');
define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD', 'https://www.eway.com.au/gateway_beagle/xmlbeagle.asp');
define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE', 'https://www.eway.com.au/gateway_beagle/test/xmlbeagle_test.asp'); //in testing mode process with REAL-TIME
define('EWAY_PAYMENT_HOSTED_REAL_TIME', 'https://www.eway.com.au/gateway/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/payment.asp');

/**
 * Class EwayLib
 */
class EwayLib
{
    public string $myGatewayURL;
    public string $myCustomerID;
    public array $myTransactionData = [];
    public array $myCurlPreferences = [];

    /**
     * EwayLib constructor.
     * @param string $customerID
     * @param string $method
     * @param bool $liveGateway
     * @throws Exception
     */
    public function __construct(string $customerID = EWAY_DEFAULT_CUSTOMER_ID, string $method = EWAY_DEFAULT_PAYMENT_METHOD, bool $liveGateway = EWAY_DEFAULT_LIVE_GATEWAY)
    {
        $this->myCustomerID = $customerID;
        switch ($method) {
            case "REAL_TIME";
                if ($liveGateway) {
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME;
                } else {
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE;
                }
                break;

            case "REAL_TIME_CVN";
                if ($liveGateway) {
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN;
                } else {
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE;
                }
                break;

            case "GEO_IP_ANTI_FRAUD";
                if ($liveGateway) {
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD;
                } else {
                    //in testing mode process with REAL-TIME
                    $this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE;
                }
                break;

            default:
                throw new Exception("EwayLib construct() - invalid method[{$method}]");
        }
    }


    /**
     * Payment Function
     * @return array
     */
    public function doPayment(): array
    {
        $xmlRequest = "<ewaygateway><ewayCustomerID>" . $this->myCustomerID . "</ewayCustomerID>";
        foreach ($this->myTransactionData as $key => $value) {
            $xmlRequest .= "<$key>$value</$key>";
        }
        $xmlRequest .= "</ewaygateway>";

        $xmlResponse = $this->sendTransactionToEway($xmlRequest);

        if ($xmlResponse != "") {
            return $this->parseResponse($xmlResponse);
        }
        die("Error in XML response from eWAY: " . $xmlResponse);
    }

    /**
     * Send XML Transaction Data and receive XML response
     * @param bool|string|int $xmlRequest
     * @return bool|string
     */
    public function sendTransactionToEway($xmlRequest)
    {
        $ch = curl_init($this->myGatewayURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        foreach ($this->myCurlPreferences as $key => $value) {
            curl_setopt($ch, $key, $value);
        }
        $xmlResponse = curl_exec($ch);

        if (curl_errno($ch) == CURLE_OK) {
            return $xmlResponse;
        }

        return false;
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    /**
     * Parse XML response from eway and place them into an array
     * @param string $xmlResponse
     * @return array
     */
    public function parseResponse(string $xmlResponse): array
    {
        $xmlParser = xml_parser_create();
        $xmlData = [];
        $index = [];
        xml_parse_into_struct($xmlParser, $xmlResponse, $xmlData, $index);
        $responseFields = [];
        foreach ($xmlData as $data) {
            if ($data["level"] == 2) {
                $responseFields[$data["tag"]] = $data["value"];
            }
        }
        return $responseFields;
    }

    /**
     * Set Transaction Data
     * Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", "CustomerInvoiceDescription", "CustomerInvoiceRef",
     * "CardHoldersName", "CardNumber", "CardExpiryMonth", "CardExpiryYear", "TrxnNumber", "Option1", "Option2", "Option3", "CVN", "CustomerIPAddress", "CustomerBillingCountry"
     * @param string $field
     * @param float $value
     */
    public function setTransactionData(string $field, float $value): void
    {
        $this->myTransactionData["eway" . $field] = Util::htmlSafe(trim($value));
    }

    /**
     * receive special preferences for Curl
     * @param string $field
     * @param float $value
     */
    public function setCurlPreferences(string $field, float $value): void
    {
        $this->myCurlPreferences[$field] = $value;
    }

    /**
     * Obtain visitor IP even if is under a proxy
     * @return mixed
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function getVisitorIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        return $ip;
    }
}
