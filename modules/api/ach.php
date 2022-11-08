<?php
/** @noinspection PhpClassConstantAccessedViaChildClassInspection */

use Inc\Claz\Biller;
use Inc\Claz\Email;
use Inc\Claz\Invoice;
use Inc\Claz\Log;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;

Log::out('ACH API page called', Log::INFO);
if ($_POST['pg_response_code'] == 'A01') {
    Log::out('ACH validate success', Log::INFO);

    //insert into payments
    $paypalData ="";
    foreach ($_POST as $key => $value) {
        $paypalData .= "\n$key: $value";
    }
    Log::out('ACH Data:', Log::INFO);
    Log::out($paypalData, Log::INFO);

    $numberOfPayments = Payment::count($_POST['pg_consumerorderid']);
    Log::out('ACH - number of times this payment is in the db: '.$numberOfPayments, Log::INFO);

    if($numberOfPayments > 0) {
        $xmlMessage = 'Online payment for invoices: '.$_POST['pg_consumerorderid'].' has already been entered';
        Log::out($xmlMessage, Log::INFO);
    } else {
        /** @noinspection PhpUnhandledExceptionInspection */
        $invoice    = Invoice::getOne($_POST['pg_consumerorderid']);
        $biller     = Biller::getOne($invoice['biller_id']);

        $customerId = $invoice['customer_id'];

        $pmtType = PaymentType::selectOrInsertWhere("ACH");
        Payment::insert([
            "ac_inv_id"         => $_POST['pg_consumerorderid'],
            "customer_id"       => $customerId,
            "ac_amount"         => $_POST['pg_total_amount'],
            "ac_notes"          => $paypalData,
            "ac_date"           => date('Y-m-d'),
            "online_payment_id" => $_POST['pg_consumerorderid'],
            "ac_payment_type"   => $pmtType
        ], $customerId);
        Log::out('ACH - payment_type='.$pmtType, Log::INFO);

        //send email
        $body  =  "A PaymentsGateway.com payment of ".$_POST['pg_total_amount']." was successfully received\n";
        $body .= "for invoice: ".$_POST['pg_consumerorderid'] ;
        $body .= " from ".$_POST['pg_billto_postal_name_company']." on ".date('m/d/Y');
        $body .= " at ".date('g:i A')."\n\nDetails:\n";
        $body .= $paypalData;

        $email = new Email();
        $email->setBody($body);
        $email->setEmailTo([$biller['email'] => $biller['name']]);
        $email->setFrom(["simpleinvoices@localhost.localdomain"]);
        $email->setSubject('PaymentsGateway.com -Instant Payment Notification - Received Payment');
        $email->send ();

        $xmlMessage = "+++++++++<br /><br />";
        $xmlMessage .= "Thank you for the payment, the details have been recorded and ". $biller['name'] ." has been notified via email.";
        $xmlMessage .= "<br /><br />+++++++++<br />";
    }
} else {
    $xmlMessage = "PaymentsGateway.com payment validate failed";
    Log::out('ACH validate failed', Log::INFO);
}
echo $xmlMessage;
