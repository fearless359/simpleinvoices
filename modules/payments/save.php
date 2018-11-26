<?php

use Inc\Claz\DomainId;
use Inc\Claz\Payment;
use Inc\Claz\SiLocal;
use Inc\Claz\Util;

global $smarty, $LANG;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$display_block = "<div class='si_message_error'>$LANG[save_payment_failure]</div>";
$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=invoices&amp;view=manage' />";

if (isset($_POST['process_payment'])) {
    try {
        $pymt_amt = SiLocal::dbStd($_POST['ac_amount']);
        $result = Payment::insert(array(
            "ac_inv_id"       => $_POST['invoice_id'],
            "ac_amount"       => $pymt_amt,
            "ac_notes"        => $_POST['ac_notes'],
            "ac_date"         => SiLocal::sqlDateWithTime($_POST['ac_date']),
            "ac_payment_type" => $_POST['ac_payment_type'],
            "domain_id"       => DomainId::get(),
            "ac_check_number" => $_POST['ac_check_number']));
        if ($result > 0) {
            $display_block = "<div class='si_message_ok'>$LANG[save_payment_success]</div>";
        }
    } catch (Exception $e) {
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');
