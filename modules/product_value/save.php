<?php

use Inc\Claz\ProductValues;

global $smarty;

// -Gates 5/5/2008 added domain_id to parameters
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";
$display_block = "<div class=\"si_message_error\">{$LANG['save_product_value_failure']}</div>";

#insert invoice_preference
if ($_POST['cancel_change'] == "Cancel") {
    $display_block = "<div class=\"si_message_warning\">{$LANG['cancelled']}</div>";
} else if (  $op === 'insert_product_value' ) {
    if (ProductValues::insert() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_value_success']}</div>";
    }
}else if (  $op === 'edit_product_value' ) {
    if (ProductValues::update()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_value_success']}</div>";
    }
}

$smarty -> assign('display_block',$display_block);
$smarty -> assign('refresh_total',$refresh_total);

$pageActive = "product_value_manage";
$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');
