<?php
/** @noinspection PhpClassConstantAccessedViaChildClassInspection */

use Inc\Claz\Biller;
use Inc\Claz\Encode;
use Inc\Claz\Email;
use Inc\Claz\Invoice;
use Inc\Claz\Log;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;
use Inc\Claz\PdoDbException;

$paypal = new paypal_class ();
$paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url

$xmlMessage = "";
$error = false;
Log::out('Paypal API page called', Log::INFO);

if ($paypal->validate_ipn()) {
    Log::out('Paypal validate success', Log::INFO);

    // insert into payments
    $paypalData = "";
    foreach ($paypal->ipn_data as $key => $value) {
        $paypalData .= "\n$key: $value";
    }

    Log::out("Paypal Data[$paypalData", Log::INFO);

    // get the domain_id from the PayPal invoice
    $customArray = explode(";", $paypal->ipn_data ['custom']);

    Log::out('Paypal - custom=' . $_POST ['custom'], Log::INFO);
    $domainId = '';
    foreach ($customArray as $key => $value) {
        if (strstr($value, "domain_id:")) {
            Log::out("Paypal - value[$value]", Log::INFO);
            $domainId = substr($value, 10);
        }
    }

    Log::out('Paypal - domain_id=' . $domainId . 'EOM', Log::INFO);

    // check if payment has already been entered
    $olPmtId = $paypal->ipn_data ['txn_id'];
    $numberOfPayments = Payment::count($olPmtId);
    Log::out('Paypal - number of times this payment is in the db: ' . $numberOfPayments, Log::INFO);
    if ($numberOfPayments > 0) {
        $xmlMessage = 'Online payment ' . $paypal->ipn_data ['tnx_id'] . ' has already been entered - exiting for domain_id=' . $domainId;
        Log::out($xmlMessage, Log::INFO);
        $error = true;
    } else {
        try {
            $invoice = Invoice::getOne($paypal->ipn_data ['invoice']);
            $biller = Biller::getOne($invoice ['biller_id']);

            $customerId = $invoice['customer_id'];

            $pmtType = PaymentType::selectOrInsertWhere("Paypal");
            Payment::insert([
                "ac_inv_id"         => $paypal->ipn_data ['invoice'],
                "customer_id"       => $customerId,
                "ac_amount"         => $paypal->ipn_data['mc_gross'],
                "ac_notes"          => $paypalData,
                "ac_date"           => date('Y-m-d', strtotime($paypal->ipn_data['payment_date'])),
                "online_payment_id" => $paypal->ipn_data['txn_id'],
                "ac_payment_type"   => $pmtType
            ], $customerId);
            Log::out('Paypal - payment_type=' . $pmtType, Log::INFO);

            // send email
            $body = "A Paypal instant payment notification was successfully received\n";
            $body .= "from " . $paypal->ipn_data ['payer_email'] . " on " . date('m/d/Y');
            $body .= " at " . date('g:i A') . "\n\nDetails:\n";
            $body .= $paypalData;

            $email = new Email();
            $email->setBody($body);
            $email->setEmailTo([$biller ['email'] => $biller['name']]);
            $email->setFrom(["simpleinvoices@localhost.localdomain"]);
            $email->setSubject('Instant Payment Notification - Received Payment');
            $email->send();

            $xmlMessage = $body;
        } catch (PdoDbException $pde) {
            $xmlMessage = "Paypal processing failed. Exception: {$pde->getMessage()}";
            $error = true;
        }
    }
} else {
    $xmlMessage = "Paypal validate failed";
    Log::out('Paypal validate failed', Log::INFO);
    $error = true;
}

header('Content-type: application/xml');
if ($error) {
    echo $xmlMessage;
} else {
    try {
        $xml = new Encode();
        $xmlOut = $xml::xml([$xmlMessage]);
        echo $xmlOut;
    } catch (Exception $exp) {
        echo $exp->getMessage();
    }
}
