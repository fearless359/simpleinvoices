<?php

use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$displayBlock = "<div class=\"si_message_error\">{$LANG['saveTaxRateFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=manage' />";

# Deal with op and add some basic sanity checking

$op = empty($_POST['op']) ? '' : $_POST['op'];
if ($op == 'create') {
    if (Taxes::verifyExists($_POST['tax_description'])) {
        $displayBlock = "<div class='si_message_error'>{$LANG['duplicateITaxDescription']}</div>";
        $refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=create' />";
    } else {
        $resultAdd = Taxes::insertTaxRate();
        if ($resultAdd > 0) {
            $displayBlock = "<div class='si_message_ok'>{$LANG['saveTaxRateSuccess']}</div>";
        }
    }

} elseif ($op == 'edit') {
    if ($_POST['orig_description'] != $_POST['tax_description'] &&
        Taxes::verifyExists($_POST['tax_description'])) {
        $displayBlock = "<div class='si_message_error'>{$LANG['duplicateITaxDescription']}</div>";
        $refreshRedirect = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=edit&amp;id={$_GET['id']}' />";
    } elseif (Taxes::updateTaxRate()) {
        $displayBlock = "<div class='si_message_ok'>{$LANG['saveTaxRateSuccess']}</div>";
    }
}

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'tax_rate');
$smarty->assign('active_tab', '#setting');
