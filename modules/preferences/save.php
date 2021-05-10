<?php

use Inc\Claz\DomainId;
use Inc\Claz\Index;
use Inc\Claz\PdoDbException;

global $LANG, $pdoDb, $smarty;

// Deal with op and add some basic sanity checking
$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : null;

$includeOnlinePayment = '';
if (isset($_POST['include_online_payment']) &&
    is_array($_POST['include_online_payment'])) {
    foreach ($_POST['include_online_payment'] as $key => $val) {
        $includeOnlinePayment .= $val;
        $keys = array_keys($_POST['include_online_payment']);
        if ($key !=  end($keys)) {
            $includeOnlinePayment .= ',';
        }
    }
}

$displayBlock = "<div class='si_message_error'>{$LANG['savePreferenceFailure']}</div>";
$refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=preferences&amp;view=manage' />";

// @formatter:off
if (  $op === 'create' ) {
    try {
        $pdoDb->setFauxPost([
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
            "language"                     => $_POST['language'],
            "locale"                       => $_POST['locale'],
            "index_group"                  => empty($_POST['index_group']) ? 0 : $_POST['index_group'],
            "set_aging"                    => $_POST['set_aging'],
            "include_online_payment"       => $includeOnlinePayment
        ]);

        $prefId = $pdoDb->request("INSERT", "preferences");
        if ($prefId == 0) {
            error_log("preferences save.php insert_preference failed");
        } else {
            $displayBlock = "<div class='si_message_ok'>{$LANG['savePreferenceSuccess']}</div>";
            // If index_group is empty, assign the pref_id assigned to it and create an index record for it.
            if ($_POST['index_group'] == 0) {
                $pdoDb->setFauxPost(["index_group" => $prefId]);
                $pdoDb->addSimpleWhere('pref_id', $prefId);
                $pdoDb->request("UPDATE", "preferences");

                // The $nextIndexId will be the "last" value assigned. If no value specified,
                // this will be zero. Otherwise it will one less than what the user specified
                // as the starting ID.
                if (empty($_POST['nextIndexId']) || $_POST['nextIndexId'] <= 0) {
                    $nextIndexId = 0;
                } else {
                    $nextIndexId = $_POST['nextIndexId'] - 1;
                }

                Index::insert($nextIndexId, $prefId);
            }
        }
    } catch (PdoDbException $pde) {
        error_log("preferences save.php insert_preference insert error: " . $pde->getMessage());
        // Set $displayBlock as it might have been set to insert success but error from update
        $displayBlock = "<div class='si_message_error'>{$LANG['savePreferenceFailure']}</div>";
    }
} elseif ($op === 'edit' ) {
    if (isset($_POST['save_preference'])) {
        try {
            $prefId = $_GET['id'];
            if ($_POST['index_group'] == 0) {
                // Create a new index record for this preference. The in
                Index::insert($_POST['startingIndexId'] - 1, $prefId);
                $_POST['index_group'] = $prefId;
            }
            $pdoDb->setFauxPost([
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
                "language"                     => $_POST['language'],
                "locale"                       => $_POST['locale'],
                "index_group"                  => $_POST['index_group'],
                "set_aging"                    => $_POST['set_aging'],
                "include_online_payment"       => $includeOnlinePayment
            ]);
            $pdoDb->addSimpleWhere("pref_id", $prefId);
            if ($pdoDb->request("UPDATE", "preferences") === false) {
                error_log("preferences save.php edit_preference id[{$_GET['id']}] update failed");
            } else {
                $displayBlock = "<div class='si_message_ok'>{$LANG['savePreferenceSuccess']}</div>";
            }
        } catch (PdoDbException $pde) {
            error_log("preferences save.php edit_preference insert error: " . $pde->getMessage());
        }
    }
}
// @formatter:on

$smarty->assign('display_block', $displayBlock);
$smarty->assign('refresh_redirect', $refreshRedirect);

$smarty->assign('pageActive', 'invPrefs');
$smarty->assign('activeTab', '#settings');

