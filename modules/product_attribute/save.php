<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $smarty;

// -Gates 5/5/2008 added domain_id to parameters 
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

# Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$refresh_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=product_attribute&amp;view=manage' />";
$display_block = "<div class=\"si_message_error\">{$LANG['save_product_attributes_failure']}</div>";

#insert invoice_preference
if ($op === 'add' ) {
    if (ProductAttributes::insert() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_attributes_success']}</div>";
    }
}else if ($op === 'edit' ) {
    if (ProductAttributes::update()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_attributes_success']}</div>";
    }
}

$smarty -> assign('display_block',$display_block);
$smarty -> assign('refresh_redirect',$refresh_redirect);

$smarty->assign('pageActive', 'product_attribute_add');
$smarty->assign('active_tab', '#product');
