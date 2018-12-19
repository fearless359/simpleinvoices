<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::isAccessAllowed();

$display_block = "<div class=\"si_message_error\">{$LANG['save_tax_rate_failure']}</div>";
$redirect_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=manage' />";

# Deal with op and add some basic sanity checking

$op = empty($_POST['op']) ? '' : $_POST['op'];

if ($op == 'add') {
    if (Taxes::verifyExists($_POST['tax_description'])) {
        $display_block = "<div class=\"si_message_error\">{$LANG['duplicate_tax_description']}</div>";
        $redirect_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=add' />";
    } else if (Taxes::insertTaxRate() > 0) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_tax_rate_success']}</div>";
    }

} else if ($op == 'edit') {
    if ($_POST['orig_description'] != $_POST['tax_description'] &&
        Taxes::verifyExists($_POST['tax_description'])) {
        $display_block = "<div class=\"si_message_error\">{$LANG['duplicate_tax_description']}</div>";
        $redirect_redirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=details&amp;id={$_GET['id']}&amp;action=edit' />";
    } else if (Taxes::updateTaxRate()) {
        $display_block = "<div class=\"si_message_ok\">{$LANG['save_tax_rate_success']}</div>";
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('redirect_redirect', $redirect_redirect);

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('active_tab', '#setting');
