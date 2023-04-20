<?php

use Inc\Claz\DomainId;
use Inc\Claz\Payment;
use Inc\Claz\Util;

global $smarty, $LANG;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$displayBlock = "<div class='si_message_error'>$LANG[savePaymentFailure]</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";

if (isset($_POST['process_payment']) && !empty($_POST['invoice_id'])) {
    $result = Payment::insert([
        "ac_inv_id"       => $_POST['invoice_id'],
        "customer_id"     => $_POST['customer_id'],
        "ac_amount"       => Util::dbStd($_POST['ac_amount']),
        "ac_notes"        => $_POST['ac_notes'],
        "ac_date"         => Util::sqlDateWithTime($_POST['ac_date']),
        "ac_payment_type" => $_POST['ac_payment_type'],
        "domain_id"       => DomainId::get(),
        "ac_check_number" => $_POST['ac_check_number']
    ], $_POST['customer_id']);
    if ($result > 0) {
        $displayBlock = "<div class='si_message_ok'>$LANG[savePaymentSuccess]</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('activeTab'   , '#money');
