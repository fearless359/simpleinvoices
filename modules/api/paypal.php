<?php

use Inc\Claz\Biller;
use Inc\Claz\Encode;
use Inc\Claz\Email;
use Inc\Claz\Invoice;
use Inc\Claz\Log;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;

$p = new paypal_class ();
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url

$xml_message = "";
Log::out ( 'Paypal API page called', \Zend_Log::INFO );

if ($p->validate_ipn ()) {
    Log::out ( 'Paypal validate success', \Zend_Log::INFO );

    // insert into payments
    $paypal_data = "";
    foreach ( $p->ipn_data as $key => $value ) {
        $paypal_data .= "\n$key: $value";
    }

    Log::out ( "Paypal Data[$paypal_data", \Zend_Log::INFO );

    // get the domain_id from the paypal invoice
    $custom_array = explode ( ";", $p->ipn_data ['custom'] );

    Log::out ( 'Paypal - custom=' . $_POST ['custom'], \Zend_Log::INFO );
    foreach ( $custom_array as $key => $value ) {
        if (strstr ( $value, "domain_id:" )) {
            Log::out ("Paypal - value[$value]", \Zend_Log::INFO );
            $domain_id = substr ( $value, 10 );
        }
    }

    Log::out ( 'Paypal - domain_id=' . $domain_id . 'EOM', \Zend_Log::INFO );

    // check if payment has already been entered
    $filter            = 'online_payment_id';
    $ol_pmt_id = $p->ipn_data ['txn_id'];
    $number_of_payments = Payment::count($filter, $ol_pmt_id);
    Log::out ( 'Paypal - number of times this payment is in the db: ' . $number_of_payments, \Zend_Log::INFO );
    if ($number_of_payments > 0) {
        $xml_message .= 'Online payment ' . $p->ipn_data ['tnx_id'] . ' has already been entered - exiting for domain_id=' . $domain_id;
        Log::out ( $xml_message, \Zend_Log::INFO );
    } else {
        $pmt_type = PaymentType::selectOrInsertWhere("Paypal");
        Payment::insert(array("ac_inv_id"         => $p->ipn_data ['invoice'],
                              "ac_amount"         => $p->ipn_data['mc_gross'],
                              "ac_notes"          => $paypal_data,
                              "ac_date"           => date('Y-m-d', strtotime($p->ipn_data['payment_date'])),
                              "online_payment_id" => $p->ipn_data['txn_id'],
                              "ac_payment_type"   => $pmt_type));
        Log::out('Paypal - payment_type=' . $pmt_type, \Zend_Log::INFO);

        $invoice = Invoice::getOne( $p->ipn_data ['invoice'] );

        $biller = Biller::getOne( $invoice ['biller_id'] );

        // send email
        $body = "A Paypal instant payment notification was successfully received\n";
        $body .= "from " . $p->ipn_data ['payer_email'] . " on " . date ( 'm/d/Y' );
        $body .= " at " . date ( 'g:i A' ) . "\n\nDetails:\n";
        $body .= $paypal_data;

        $email = new Email();
        $email->setBody($body);
        $email->setTo($biller ['email']);
        $email->setFrom("simpleinvoices@localhost.localdomain");
        $email->setSubject('Instant Payment Notification - Received Payment');
        $email->send ();

        $xml_message .= $body;
    }
} else {
    $xml_message .= "Paypal validate failed";
    Log::out ( 'Paypal validate failed', \Zend_Log::INFO );
}

header ( 'Content-type: application/xml' );
try {
    $xml = new Encode();
    $xml_out = $xml->xml( $xml_message );
    echo $xml_out;
} catch ( Exception $e ) {
    echo $e->getMessage ();
}
