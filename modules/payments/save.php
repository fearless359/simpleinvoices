<?php
global $smarty, $LANG;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$display_block = "<div class='si_message_error'>$LANG[save_payment_failure]</div>";
$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=payments&view=manage' />";

if (isset($_POST['process_payment'])) {
    // @formatter:off
    try {
        $pymt_amt = siLocal::dbStd($_POST['ac_amount']);
        $result = Payment::insert(array(
            "ac_inv_id"       => $_POST['invoice_id'],
            "ac_amount"       => $pymt_amt,
            "ac_notes"        => $_POST['ac_notes'],
            "ac_date"         => sqlDateWithTime($_POST['ac_date']),
            "ac_payment_type" => $_POST['ac_payment_type'],
            "domain_id"       => domain_id::get()));
        $display_block = "<div class='si_message_ok'>$LANG[save_payment_success]</div>";
    } catch (Exception $e) {
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');
