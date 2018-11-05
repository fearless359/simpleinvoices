<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

global $pdoDb, $smarty;

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

$include_online_payment = '';
if (isset($_POST['include_online_payment']) &&
    is_array($_POST['include_online_payment'])) {
    foreach ($_POST['include_online_payment'] as $k => $v) {
        $include_online_payment .= $v;
        if ($k !=  end(array_keys($_POST['include_online_payment']))) {
            $include_online_payment .= ',';
        }
    }
}

$display_block = "<div class=\"si_message_error\">{$LANG['save_preference_failure']}</div>";
$refresh_total = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=preferences&amp;view=manage\" />";

if (  $op === 'insert_preference' ) {
    // @formatter:off
    try {
        $pdoDb->setFauxPost(array(
            "domain_id"                    => DomainId::get(),
            "pref_description"             => $_POST['p_description'],
            "pref_currency_sign"           => $_POST['p_currency_sign'],
            "currency_code"                => $_POST['currency_code'],
            "pref_inv_heading"             => $_POST['p_inv_heading'],
            "pref_inv_wording"             => $_POST['p_inv_wording'],
            "pref_inv_detail_heading"      => $_POST['p_inv_detail_heading'],
            "pref_inv_detail_line"         => $_POST['p_inv_detail_line'],
            "pref_inv_payment_method"      => $_POST['p_inv_payment_method'],
            "pref_inv_payment_line1_name"  => $_POST['p_inv_payment_line1_name'],
            "pref_inv_payment_line1_value" => $_POST['p_inv_payment_line1_value'],
            "pref_inv_payment_line2_name"  => $_POST['p_inv_payment_line2_name'],
            "pref_inv_payment_line2_value" => $_POST['p_inv_payment_line2_value'],
            "pref_enabled"                 => $_POST['pref_enabled'],
            "status"                       => $_POST['status'],
            "locale"                       => $_POST['locale'],
            "language"                     => $_POST['locale'],
            "index_group"                  => (empty($_POST['index_group']) ? 0 : $_POST['index_group']),
            "include_online_payment"       => $include_online_payment
        ));

        $id = $pdoDb->request("INSERT", "preferences");
        if ($id == 0) {
            error_log("preferences save.php insert_preference failed");
        } else {
            $display_block = "<div class=\"si_message_ok\">{$LANG['save_preference_success']}</div>";
            // If index_group is empty, assign the pref_id assigned to it.
            if (empty($_POST['index_group'])) {
                $pdoDb->setFauxPost(array("index_group" => $id));
                $pdoDb->addSimpleWhere('pref_id', $id);
                $pdoDb->request("UPDATE", "preferences");
            }
        }
    } catch (PdoDbException $pde) {
        error_log("preferences save.php insert_preference insert error: " . $pde->getMessage());
        // Set $display_block as it might have been set to insert success but error from update
        $display_block = "<div class=\"si_message_error\">{$LANG['save_preference_failure']}</div>";
    }
} else if ($op === 'edit_preference' ) {
    if (isset($_POST['save_preference'])) {
        try {
            $pdoDb->setFauxPost(array(
                "pref_description"             => $_POST['pref_description'],
                "pref_currency_sign"           => $_POST['pref_currency_sign'],
                "currency_code"                => $_POST['currency_code'],
                "pref_inv_heading"             => $_POST['pref_inv_heading'],
                "pref_inv_wording"             => $_POST['pref_inv_wording'],
                "pref_inv_detail_heading"      => $_POST['pref_inv_detail_heading'],
                "pref_inv_detail_line"         => $_POST['pref_inv_detail_line'],
                "pref_inv_payment_method"      => $_POST['pref_inv_payment_method'],
                "pref_inv_payment_line1_name"  => $_POST['pref_inv_payment_line1_name'],
                "pref_inv_payment_line1_value" => $_POST['pref_inv_payment_line1_value'],
                "pref_inv_payment_line2_name"  => $_POST['pref_inv_payment_line2_name'],
                "pref_inv_payment_line2_value" => $_POST['pref_inv_payment_line2_value'],
                "pref_enabled"                 => $_POST['pref_enabled'],
                "status"                       => $_POST['status'],
                "locale"                       => $_POST['locale'],
                "language"                     => $_POST['language'],
                "index_group"                  => $_POST['index_group'],
                "include_online_payment"       => $include_online_payment
            ));
            $pdoDb->addSimpleWhere("pref_id", $_GET['id']);
            if ($pdoDb->request("UPDATE", "preferences") === false) {
                error_log("preferences save.php edit_preference id[{$_GET['id']}] update failed");
            } else {
                $display_block = "<div class=\"si_message_ok\">{$LANG['save_preference_success']}</div>";
            }
        } catch (PdoDbException $pde) {
            error_log("preferences save.php edit_preference insert error: " . $pde->getMessage());
        }
        // @formatter:on
    }
}

$smarty->assign('display_block', $display_block);
$smarty->assign('refresh_total', $refresh_total);

$smarty->assign('pageActive', 'preference');
$smarty->assign('active_tab', '#setting');

