<?php

use Inc\Claz\ProductValues;
use Inc\Claz\Util;

global $smarty;

// -Gates 5/5/2008 added domain_id to parameters
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$op = !empty($_POST['op']) ? $_POST['op'] : null;

$refresh_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";
$display_block = "<div class=\"si_message_error\">{$LANG['save_product_value_failure']}</div>";
$pageActive = 'product_value_manager';

#insert invoice_preference
if ( $op === 'add') {
    if (ProductValues::isDuplicate($_POST['attribute_id'], $_POST['value'])) {
        $refresh_redirect = "<meta http-equiv='refresh' content='5;url=index.php?module=product_value&amp;view=add' />";
        $display_block = "<div class=\"si_message_error\">{$LANG['duplicate_product_value']}</div>";
        $pageActive = 'product_value_add';
    } else if (ProductValues::insert() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_value_success']}</div>";
    }
}else if ( $op === 'edit') {
    if (ProductValues::update()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_product_value_success']}</div>";
    }
}

$smarty -> assign('display_block',$display_block);
$smarty -> assign('refresh_redirect',$refresh_redirect);

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('active_tab', '#product');
