<?php
use Inc\Claz\ProductGroups;
use Inc\Claz\Util;

global $LANG, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// Deal with op and add some basic sanity checking
$displayMessage = "<div class='si_message_error'>{$LANG['saveProductGroupFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=product_groups&amp;view=manage' />";

$op = $_POST['op'];
if ($op === 'create') {
    if (isset($_POST['save_product_group'])) {
        if (ProductGroups::insertGroup()) {
            $displayMessage = "<div class='si_message_ok'>{$LANG['saveProductGroupSuccess']}</div>";
        }
    }
} elseif ($op === 'edit') {
    if (isset($_POST ['save_product_group'])) {
        if (ProductGroups::updateGroup()) {
            $displayMessage = "<div class='si_message_ok'>{$LANG['saveProductGroupSuccess']}</div>";
        }
    }
}

$smarty->assign('display_message', $displayMessage);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign( 'pageActive', 'productGroups' );
$smarty->assign( 'activeTab', '#product' );
