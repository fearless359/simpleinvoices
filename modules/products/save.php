<?php
use Inc\Claz\Product;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$displayMessage = "<div class='si_message_error'>{$LANG['save_product_failure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=products&amp;view=manage' />";

$op = $_POST['op'];
if ($op === 'create') {
    if (isset($_POST['save_product'])) {
        if (Product::insertProduct() > 0) {
            $displayMessage = "<div class='si_message_ok'>{$LANG['save_product_success']}</div>";
        }
    }
} elseif ($op === 'edit') {
    if (isset($_POST ['save_product'])) {
        if (Product::updateProduct()) {
            $displayMessage = "<div class='si_message_ok'>{$LANG['save_product_success']}</div>";
        }
    }
}

$smarty->assign('display_message', $displayMessage);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign( 'pageActive', 'product_manage' );
$smarty->assign( 'active_tab', '#product' );
