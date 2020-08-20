<?php

use Inc\Claz\Biller;
use Inc\Claz\Encode;
use Inc\Claz\Email;
use Inc\Claz\Invoice;
use Inc\Claz\Log;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;

$paypal = new paypal_class ();
$paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url

$xmlMessage = "";
Log::out ( 'Paypal API page called', Zend_Log::INFO );

if ($paypal->validate_ipn ()) {
    Log::out ( 'Paypal validate success', Zend_Log::INFO );

    // insert into payments
    $paypalData = "";
    foreach ( $paypal->ipn_data as $key => $value ) {
        $paypalData .= "\n$key: $value";
    }

    Log::out ( "Paypal Data[$paypalData", Zend_Log::INFO );

    // get the domain_id from the paypal invoice
    $customArray = explode ( ";", $paypal->ipn_data ['custom'] );

    Log::out ( 'Paypal - custom=' . $_POST ['custom'], Zend_Log::INFO );
    foreach ( $customArray as $key => $value ) {
        if (strstr ( $value, "domain_id:" )) {
            Log::out ("Paypal - value[$value]", Zend_Log::INFO );
            $domainId = substr ( $value, 10 );
        }
    }

    Log::out ( 'Paypal - domain_id=' . $domainId . 'EOM', Zend_Log::INFO );

    // check if payment has already been entered
    $olPmtId = $paypal->ipn_data ['txn_id'];
    $numberOfPayments = Payment::count($olPmtId);
    Log::out ( 'Paypal - number of times this payment is in the db: ' . $numberOfPayments, Zend_Log::INFO );
    if ($numberOfPayments > 0) {
        $xmlMessage .= 'Online payment ' . $paypal->ipn_data ['tnx_id'] . ' has already been entered - exiting for domain_id=' . $domainId;
        Log::out ( $xmlMessage, Zend_Log::INFO );
    } else {
        $pmtType = PaymentType::selectOrInsertWhere("Paypal");
        Payment::insert([
            "ac_inv_id"         => $paypal->ipn_data ['invoice'],
            "ac_amount"         => $paypal->ipn_data['mc_gross'],
            "ac_notes"          => $paypalData,
            "ac_date"           => date('Y-m-d', strtotime($paypal->ipn_data['payment_date'])),
            "online_payment_id" => $paypal->ipn_data['txn_id'],
            "ac_payment_type"   => $pmtType
        ]);
        Log::out('Paypal - payment_type=' . $pmtType, Zend_Log::INFO);

        $invoice = Invoice::getOne( $paypal->ipn_data ['invoice'] );

        $biller = Biller::getOne( $invoice ['biller_id'] );

        // send email
        $body = "A Paypal instant payment notification was successfully received\n";
        $body .= "from " . $paypal->ipn_data ['payer_email'] . " on " . date ( 'm/d/Y' );
        $body .= " at " . date ( 'g:i A' ) . "\n\nDetails:\n";
        $body .= $paypalData;

        $email = new Email();
        $email->setBody($body);
        $email->setEmailTo($biller ['email']);
        $email->setFrom("simpleinvoices@localhost.localdomain");
        $email->setSubject('Instant Payment Notification - Received Payment');
        $email->send ();

        $xmlMessage .= $body;
    }
} else {
    $xmlMessage .= "Paypal validate failed";
    Log::out ( 'Paypal validate failed', Zend_Log::INFO );
}

header ( 'Content-type: application/xml' );
try {
    $xml = new Encode();
    $xmlOut = $xml->xml( $xmlMessage );
    echo $xmlOut;
} catch ( Exception $exp ) {
    echo $exp->getMessage ();
}
