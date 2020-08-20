<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Eway;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\Preferences;
use Inc\Claz\ProductAttributes;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

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
Util::directAccessAllowed();

$invoiceId = $_GET['id'];

$invoice              = Invoice::getOne($invoiceId);
$invoiceNumberOfTaxes = Invoice::numberOfTaxesForInvoice($invoiceId);
$invoiceType          = Invoice::getInvoiceType($invoice['type_id']);
$customer             = Customer::getOne($invoice['customer_id']);
$biller               = Biller::getOne($invoice['biller_id']);
$preference           = Preferences::getOne($invoice['preference_id']);
$defaults             = SystemDefaults::loadValues();
$invoiceItems         = Invoice::getInvoiceItems($invoiceId);

$ewayCheck          = new Eway();
$ewayCheck->invoice = $invoice;
$ewayPreCheck       = $ewayCheck->preCheck();
//Invoice Age - number of days
if ($invoice['owing'] > 0 ) {
    $invoiceAgeDays = $invoice['age_days'];
    $invoiceAge = "$invoiceAgeDays {$LANG['days']}";
}
else {
    $invoiceAge = "";
}

$urlForPdf = "index.php?module=export&amp;view=pdf&id=" . $invoice['id'];

$invoice['url_for_pdf'] = $urlForPdf;

$customFieldLabels = CustomFields::getLabels(true);

$customFields = [];
for($ndx=1; $ndx<=4; $ndx++) {
    $customFields[$ndx] = CustomFields::showCustomField("invoice_cf{$ndx}", $invoice["custom_field{$ndx}"],
                                                         "read", 'summary', '', '',
                                                         5, ':');
}

$attributes = ProductAttributes::getAll();

//Customer accounts sections
$customerAccount = [];
$customerAccount['total'] = Customer::calcCustomerTotal($customer['id'], true);
$customerAccount['paid']  = Payment::calcCustomerPaid($customer['id'] , true);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty->assign("attributes"          , $attributes);
$smarty->assign("customFields"        , $customFields);
$smarty->assign("customFieldLabels"   , $customFieldLabels);
$smarty->assign("invoiceAge"          , $invoiceAge);
$smarty->assign("invoiceNumberOfTaxes", $invoiceNumberOfTaxes);
$smarty->assign("invoiceItems"        , $invoiceItems);
$smarty->assign("defaults"            , $defaults);
$smarty->assign("preference"          , $preference);
$smarty->assign("biller"              , $biller);
$smarty->assign("customer"            , $customer);
$smarty->assign("invoiceType"         , $invoiceType);
$smarty->assign("invoice"             , $invoice);
$smarty->assign("wordprocessor"       , $config->export->wordprocessor);
$smarty->assign("spreadsheet"         , $config->export->spreadsheet);
$smarty->assign("customerAccount"     , $customerAccount);
$smarty->assign("ewayPreCheck"        , $ewayPreCheck);

$smarty->assign('pageActive'   , 'invoice');
$smarty->assign('subPageActive', 'invoice_view');
$smarty->assign('active_tab'   , '#money');
// @formatter:on
