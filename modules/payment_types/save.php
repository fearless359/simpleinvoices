<?php

use Inc\Claz\PaymentType;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::isAccessAllowed();

$display_block = "<div class=\"si_message_error\">{$LANG['save_payment_type_failure']}</div>";
$refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=payment_types&amp;view=manage\"/>";

// Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? addslashes($_POST['op']) : null;

if ($op === 'add') {
    if (PaymentType::insert()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_payment_type_success']}</div>";
    }
} else if ($op === 'edit') {
    if (PaymentType::update($_GET['id'])) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_payment_type_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_redirect', $refresh_redirect);

$smarty->assign('pageActive', 'payment_type');
$smarty->assign('active_tab', '#setting');
