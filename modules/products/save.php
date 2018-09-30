<?php
global $smarty;
// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

// Deal with op and add some basic sanity checking
$display_class = "si_message_warning";
$display_message = $LANG['cancelled'];
$refresh_redirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=products&amp;view=manage' />";

$op = (!empty ( $_POST ['op'] ) ? $_POST['op'] : NULL);
if ($op === 'insert_product') {
    try {
        if (isset($_POST['save_product'])) {
            if (Product::insertProduct() > 0) {
                $display_class = "si_message_ok";
                $display_message = $LANG['save_product_success'];
            } else {
                $display_class = "si_message_error";
                $display_message = $LANG['save_product_failure'];
            }
        }
    } catch (Zend_Locale_Exception $zle) {
        $display_class = "si_message_error";
        $display_message = $LANG['save_product_failure'];
    }
} else if ($op === 'edit_product') {
    try {
        if (isset($_POST ['save_product'])) {
            if (Product::updateProduct()) {
                $display_class = "si_message_ok";
                $display_message = $LANG['save_product_success'];
            } else {
                $display_class = "si_message_error";
                $display_message = $LANG['save_product_failure'];
            }
        }
    } catch (Zend_Locale_Exception $zle) {
        $display_class = "si_message_error";
        $display_message = $LANG['save_product_failure'];
    }
}

$smarty->assign('display_class', $display_class);
$smarty->assign('display_message', $display_message);
$smarty->assign('refresh_redirect', $refresh_redirect);
$smarty->assign( 'pageActive', 'product_manage' );
$smarty->assign( 'active_tab', '#product' );
