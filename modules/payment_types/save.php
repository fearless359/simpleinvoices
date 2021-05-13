<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::directAccessAllowed();

$displayBlock = "<div class='si_message_error'>{$LANG['savePaymentTypeFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=payment_types&amp;view=manage'/>";

// Deal with op and add some basic sanity checking
$op = $_POST['op'];

if ($op === 'create') {
    if (PaymentType::insert()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['savePaymentTypeSuccess']}</div>";
    }
} elseif ($op === 'edit') {
    if (PaymentType::update($_GET['id'])) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['savePaymentTypeSuccess']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'pymtTypes');
$smarty->assign('activeTab', '#settings');
