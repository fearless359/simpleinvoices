<?php

use Inc\Claz\ProductAttributes;
use Inc\Claz\Util;

global $LANG, $smarty;

// -Gates 5/5/2008 added domain_id to parameters 
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

# Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=product_attribute&amp;view=manage' />";
$displayBlock = "<div class=\"si_message_error\">{$LANG['save_product_attributes_failure']}</div>";

#insert invoice_preference
if ($op === 'add' ) {
    if (ProductAttributes::insert() > 0) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_product_attributes_success']}</div>";
    }
}elseif ($op === 'edit' ) {
    if (ProductAttributes::update()) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['save_product_attributes_success']}</div>";
    }
}

$smarty->assign('display_block',$displayBlock);
$smarty->assign('refresh_redirect',$refreshRedirect);

$smarty->assign('pageActive', 'product_attribute_add');
$smarty->assign('active_tab', '#product');
