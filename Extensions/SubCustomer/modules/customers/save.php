<?php
/*
 *  Script: save.php
 *      Customers save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-10-03 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// Deal with op and add some basic sanity checking
$op = ! empty ( $_POST ['op'] ) ? addslashes ( $_POST ['op'] ) : NULL;

$display_block = "<div class=\"si_message_error\">{$LANG['save_customer_failure']}</div>";
$refresh_total = "<meta http-equiv=\"refresh\" content=\"2;url=index.php?module=customers&amp;view=manage\" />";

$saved = false;
if ($op === "insert_customer") {
    if (SubCustomers::insertCustomer()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
    }
} else if ($op === 'edit_customer' && isset($_POST['save_customer'])) {
    if (SubCustomers::updateCustomer()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_customer_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign ( 'pageActive', 'customer' );
$smarty->assign ( 'active_tab', '#people' );
