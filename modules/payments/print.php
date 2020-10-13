<?php
/** @noinspection DuplicatedCode */

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PaymentType;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\Util;

global $LANG, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$menu = false;
$payment = Payment::getOne($_GET['id']);

// Get Invoice preference - so can link from this screen back to the invoice
$biller = Biller::getOne($payment['biller_id']);
$customer = Customer::getOne($payment['customer_id']);
$customFieldLabels = CustomFields::getLabels(true);

$billerInfo = [];
$billerInfo[] = [$LANG['nameUc'].':',$biller['name']];
$needsLbl = true;
if (!empty($biller['street_address'])) {
    $billerInfo[] = [$LANG['addressUc'].':',$biller['street_address']];
    $needsLbl = false;
}
if (!empty($biller['street_address2'])) {
    $billerInfo[] = [$needsLbl ? $LANG['addressUc'].':' : '',$biller['street_address2']];
    $needsLbl = false;
}
if (!empty($biller['city']) || !empty($biller['state']) || !empty($biller['zip_code'])) {
    $tmpLn = "";
    if (!empty($biller['city'])) {
        $tmpLn .= $biller['city'] . ', ';
    }

    if (!empty($biller['state'])) {
        $tmpLn .= $biller['state'] . ' ';
    }

    if (!empty($biller['zip_code'])) {
        $tmpLn .= $biller['zip_code'];
    }

    $billerInfo[] = [$needsLbl ? $LANG['addressUc'].':' : "", $tmpLn];
    $needsLbl = false;
}

if (!empty($biller['country'])) {
    $billerInfo[] = [$needsLbl ? $LANG['addressUc'].':' : '', $biller['country']];
}

if (!empty($biller['phone'])) {
    $billerInfo[] = [$LANG['phoneUc'], $biller['phone']];
}

if (!empty($biller['fax'])) {
    $billerInfo[] = [$LANG['fax'], $biller['fax']];
}

if (!empty($biller['mobile_phone'])) {
    $billerInfo[] = [$LANG['mobileShort'], $biller['mobile_phone']];
}

if (!empty($biller['email'])) {
    $billerInfo[] = [$LANG['email'], $biller['email']];
}

if (!empty($customFieldLabels['biller_cf1']) &&
    !empty($biller['custom_field1'])) {
    $billerInfo[] = [$customFieldLabels['biller_cf1'], $biller['custom_field1']];
}

if (!empty($customFieldLabels['biller_cf2']) &&
    !empty($biller['custom_field2'])) {
    $billerInfo[] = [$customFieldLabels['biller_cf2'], $biller['custom_field2']];
}

if (!empty($customFieldLabels['biller_cf3']) &&
    !empty($biller['custom_field3'])) {
    $billerInfo[] = [$customFieldLabels['biller_cf3'], $biller['custom_field3']];
}

if (!empty($customFieldLabels['biller_cf4']) &&
    !empty($biller['custom_field4'])) {
    $billerInfo[] = [$customFieldLabels['biller_cf4'], $biller['custom_field4']];
}

$custInfo = [];
$custInfo[] = [$LANG['customerUc'].':', $customer['name']];
if (!empty($customer['attention'])) {
    $custInfo[] = [$LANG['attentionShort'].':',$customer['attention']];
}

$needsLbl = true;
if (!empty($customer['street_address'])) {
    $custInfo[] = [$LANG['addressUc'].':',$customer['street_address']];
    $needsLbl = false;
}

if (!empty($customer['street_address2'])) {
    $custInfo[] = [$needsLbl ? $LANG['addressUc'].':' : '',$customer['street_address2']];
    $needsLbl = false;
}

if (!empty($customer['city']) || !empty($customer['state']) || !empty($customer['zip_code'])) {
    // @formatter:off
    $tmpLn = "";
    if (!empty($customer['city'])) {
        $tmpLn .= $customer['city'] . ', ';
    }
    if (!empty($customer['state'])) {
        $tmpLn .= $customer['state'] . ' ';
    }
    if (!empty($customer['zip_code'])) {
        $tmpLn .= $customer['zip_code'];
    }

    $custInfo[] = [$needsLbl ? $LANG['addressUc'].':' : "", $tmpLn];
    $needsLbl   = false;
    // @formatter:on
}

if (!empty($customer['country'])) {
    $custInfo[] = [$needsLbl ? $LANG['addressUc'].':':'', $customer['country']];
}

if (!empty($customer['phone'])) {
    $custInfo[] = [$LANG['phoneUc'], $customer['phone']];
}

if (!empty($customer['fax'])) {
    $custInfo[] = [$LANG['fax'], $customer['fax']];
}

if (!empty($customer['mobile_phone'])) {
    $custInfo[] = [$LANG['mobileShort'], $customer['mobile_phone']];
}

if (!empty($customer['email'])) {
    $custInfo[] = [$LANG['email'], $customer['email']];
}

if (!empty($customFieldLabels['customer_cf1']) && !empty($customer['custom_field1'])) {
    $custInfo[] = [$customFieldLabels['customer_cf1'], $customer['custom_field1']];
}

if (!empty($customFieldLabels['customer_cf2']) &&
    !empty($customer['custom_field2'])) {
    $custInfo[] = [$customFieldLabels['customer_cf2'], $customer['custom_field2']];
}

if (!empty($customFieldLabels['customer_cf3']) &&
    !empty($customer['custom_field3'])) {
    $custInfo[] = [$customFieldLabels['customer_cf3'], $customer['custom_field3']];
}

if (!empty($customFieldLabels['customer_cf4']) &&
    !empty($customer['custom_field4'])) {
    $custInfo[] = [$customFieldLabels['customer_cf4'], $customer['custom_field4']];
}

try {
    $invoice = Invoice::getOne($payment['ac_inv_id']);

    $smarty->assign("invoiceType", Invoice::getInvoiceType($invoice['type_id']));
    $smarty->assign("paymentType", PaymentType::getOne($payment['ac_payment_type']));
    $smarty->assign("preference", Preferences::getOne($invoice['preference_id']));
} catch (PdoDbException $pde) {
    exit("modules/payments/print.php Unexpected error: {$pde->getMessage()}");
}

// @formatter:off
$smarty->assign("menu"             , $menu);
$smarty->assign("payment"          , $payment);
$smarty->assign("invoice"          , $invoice);
$smarty->assign("biller"           , $biller);
$smarty->assign("biller_info"      , $billerInfo);
$smarty->assign("biller_info_count", count($billerInfo));
$smarty->assign("logo"             , str_replace(" ", "%20", Util::getLogo($biller)));
$smarty->assign("customer"         , $customer);
$smarty->assign("cust_info"        , $custInfo);
$smarty->assign("cust_info_count"  , count($custInfo));
$smarty->assign("customFieldLabels", $customFieldLabels);
$smarty->assign('pageActive'       , 'payment');
$smarty->assign('activeTab'       , '#money');
// @formatter:on
