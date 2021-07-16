<?php

use Inc\Claz\ProductAttributeValues;
use Inc\Claz\Util;

global $LANG, $smarty;

// -Gates 5/5/2008 added domain_id to parameters
//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$op = !empty($_POST['op']) ? $_POST['op'] : null;

$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=product_attribute_values&amp;view=manage' />";
$displayBlock = "<div class=\"si_message_error\">{$LANG['saveProductAttributeValueFailure']}</div>";

#insert invoice_preference
if ( $op === 'create') {
    if (ProductAttributeValues::isDuplicate($_POST['attribute_id'], $_POST['value'])) {
        $refreshRedirect = "<meta http-equiv='refresh' content='5;url=index.php?module=product_attribute_values&amp;view=create' />";
        $displayBlock = "<div class=\"si_message_error\">{$LANG['duplicateProductAttributeValue']}</div>";
        $smarty->assign('subPageActive', 'create');
    } elseif (ProductAttributeValues::insert() > 0) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveProductAttributeValueSuccess']}</div>";
    }
}elseif ( $op === 'edit') {
    if (ProductAttributeValues::update()) {
        $displayBlock = "<div class=\"si_message_ok\">{$LANG['saveProductAttributeValueSuccess']}</div>";
    }
}

$smarty->assign('display_block',$displayBlock);
$smarty->assign('refresh_redirect',$refreshRedirect);

$smarty->assign('pageActive', 'productAttributeValues');
$smarty->assign('activeTab', '#product');
