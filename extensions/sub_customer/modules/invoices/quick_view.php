<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Eway;
use Inc\Claz\Extensions;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 * Script: quick_view.php
 *     Quick view model
 *
 * Authors:
 *     Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *     2016 -10-04
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $config, $LANG, $smarty;

Util::directAccessAllowed();

$invoice_id = $_GET['id'];
// @formatter:off
$invoice                 = Invoice::getOne($invoice_id);
$invoice_number_of_taxes = Invoice::numberOfTaxesForInvoice($invoice_id);
$invoice_type            = Invoice::getInvoiceType($invoice['type_id']);
$customer                = Customer::getOne($invoice['customer_id']);
$biller                  = Biller::getOne($invoice['biller_id']);
$preference              = Preferences::getOne($invoice['preference_id']);
$defaults                = SystemDefaults::loadValues();
$invoiceItems            = Invoice::getInvoiceItems($invoice_id);
$sub_customer            = Customer::getOne($invoice['custom_field1']);

$eway_check          = new Eway();
$eway_check->invoice = $invoice;
$eway_pre_check      = $eway_check->pre_check();

// Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
    $invoice_age = "$invoice_age_days {$LANG['days']}";
} else {
    $invoice_age ="";
}

$url_for_pdf = "index.php?module=export&amp;view=pdf&amp;id=" . $invoice['id'];

$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = CustomFields::getLabels(true);

$customFields = array();
for($i=1;$i<=4;$i++) {
    $customFields[$i] = CustomFields::showCustomField("invoice_cf$i"  , $invoice["custom_field$i"],
                                                        "read"          , 'details_screen summary'  ,
                                                        'details_screen', 'details_screen'          ,
                                                        5               , ':');
}

$customerAccount = null;
$customerAccount['total'] = Customer::calcCustomerTotal($customer['id']);
$customerAccount['paid']  = Payment::calcCustomerPaid($customer['id']);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty->assign("customFields"           , $customFields);
$smarty->assign("customFieldLabels"      , $customFieldLabels);
$smarty->assign("invoice_age"            , $invoice_age);
$smarty->assign("invoice_number_of_taxes", $invoice_number_of_taxes);
$smarty->assign("invoiceItems"           , $invoiceItems);
$smarty->assign("defaults"               , $defaults);
$smarty->assign("preference"             , $preference);
$smarty->assign("biller"                 , $biller);
$smarty->assign("customer"               , $customer);
$smarty->assign("sub_customer"           , $sub_customer);
$smarty->assign("invoice_type"           , $invoice_type);
$smarty->assign("invoice"                , $invoice);
$smarty->assign("wordprocessor"          , $config->export->wordprocessor);
$smarty->assign("spreadsheet"            , $config->export->spreadsheet);
$smarty->assign("customerAccount"        , $customerAccount);
$smarty->assign("eway_pre_check"         , $eway_pre_check);

$smarty->assign('pageActive'             , 'invoice');
$smarty->assign('subPageActive'          , 'invoice_view');
$smarty->assign('active_tab'             , '#money');
// @formatter:on
