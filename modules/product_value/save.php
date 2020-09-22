<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $LANG, $smarty;

// -Gates 5/5/2008 added domain_id to parameters
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$op = !empty($_POST['op']) ? $_POST['op'] : null;

$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";
$displayBlock = "<div class=\"si_message_error\">{$LANG['saveProductValueFailure']}</div>";
$pageActive = 'product_value_manager';

#insert invoice_preference
if ( $op === 'create') {
    if (ProductValues::isDuplicate($_POST['attribute_id'], $_POST['value'])) {
        $refreshRedirect = "<meta http-equiv='refresh' content='5;url=index.php?module=product_value&amp;view=create' />";
        $displayBlock = "<div class=\"si_message_error\">{$LANG['duplicateIProductValue']}</div>";
        $pageActive = 'product_value_add';
    } elseif (ProductValues::insert() > 0) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveProductValueSuccess']}</div>";
    }
}elseif ( $op === 'edit') {
    if (ProductValues::update()) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveProductValueSuccess']}</div>";
    }
}

$smarty->assign('display_block',$displayBlock);
$smarty->assign('refresh_redirect',$refreshRedirect);

$smarty->assign('pageActive', $pageActive);
$smarty->assign('active_tab', '#product');
