<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Eway;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;

/*
 * Script: quick_view.php
 * Quick view model
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *   2008-01-03
 *
 * License:
 *   GPL v2 or above
 *     
 * Website:
 *   https://simpleinvoices.group */
global $config, $LANG, $smarty;

// @formatter:off
checkLogin();

$invoice_id = $_GET['id'];

$invoice                 = Invoice::getInvoice($invoice_id);
$invoice_number_of_taxes = Invoice::numberOfTaxesForInvoice($invoice_id);
$invoice_type            = Invoice::getInvoiceType($invoice['type_id']);
$customer                = Customer::get($invoice['customer_id']);
$biller                  = Biller::select($invoice['biller_id']);
$preference              = Preferences::getPreference($invoice['preference_id']);
$defaults                = SystemDefaults::loadValues();
$invoiceItems            = Invoice::getInvoiceItems($invoice_id);

$eway_check          = new Eway();
$eway_check->invoice = $invoice;
$eway_pre_check      = $eway_check->pre_check();

//Invoice Age - number of days
if ($invoice['owing'] > 0 ) {
    $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
    $invoice_age      = "$invoice_age_days {$LANG['days']}";
}
else {
    $invoice_age = "";
}

$url_for_pdf = "index.php?module=export&amp;view=pdf&id=" . $invoice['id'];

$invoice['url_for_pdf'] = $url_for_pdf;

$customFieldLabels = getCustomFieldLabels(true);

$customFields = array();
for($i=1;$i<=4;$i++) {
    $customFields[$i] = CustomFields::showCustomField("invoice_cf$i", $invoice["custom_field$i"],
                                                      "read", 'summary',
                                                      '', '',
                                                      5, ':');
}

$attributes = array();
try {
    $pdoDb->setSelectAll(true);
    $attributes = $pdoDb->request("SELECT", "products_attributes");
} catch (PdoDbException $pde) {
    error_log("modules/invoices/quick_view.php - error: " . $pde->getMessage());
}

//Customer accounts sections
$customerAccount = null;
$customerAccount['total'] = Customer::calc_customer_total($customer['id'], true);
$customerAccount['paid']  = Payment::calc_customer_paid($customer['id'] , true);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty->assign("attributes"             , $attributes);
$smarty->assign('pageActive'             , 'invoice');
$smarty->assign('subPageActive'          , 'invoice_view');
$smarty->assign('active_tab'             , '#money');
$smarty->assign("customFields"           , $customFields);
$smarty->assign("customFieldLabels"      , $customFieldLabels);
$smarty->assign("invoice_age"            , $invoice_age);
$smarty->assign("invoice_number_of_taxes", $invoice_number_of_taxes);
$smarty->assign("invoiceItems"           , $invoiceItems);
$smarty->assign("defaults"               , $defaults);
$smarty->assign("preference"             , $preference);
$smarty->assign("biller"                 , $biller);
$smarty->assign("customer"               , $customer);
$smarty->assign("invoice_type"           , $invoice_type);
$smarty->assign("invoice"                , $invoice);
$smarty->assign("wordprocessor"          , $config->export->wordprocessor);
$smarty->assign("spreadsheet"            , $config->export->spreadsheet);
$smarty->assign("customerAccount"        , $customerAccount);
$smarty->assign("eway_pre_check"         , $eway_pre_check);
// @formatter:on
