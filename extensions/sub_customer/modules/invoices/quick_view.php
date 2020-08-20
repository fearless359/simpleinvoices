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

$invoiceId = $_GET['id'];
// @formatter:off
$invoice              = Invoice::getOne($invoiceId);
$invoiceNumberOfTaxes = Invoice::numberOfTaxesForInvoice($invoiceId);
$invoiceType         = Invoice::getInvoiceType($invoice['type_id']);
$customer             = Customer::getOne($invoice['customer_id']);
$biller               = Biller::getOne($invoice['biller_id']);
$preference           = Preferences::getOne($invoice['preference_id']);
$defaults             = SystemDefaults::loadValues();
$invoiceItems         = Invoice::getInvoiceItems($invoiceId);
$subCustomer         = Customer::getOne($invoice['custom_field1']);

$ewayCheck          = new Eway();
$ewayCheck->invoice = $invoice;
$ewayPreCheck       = $ewayCheck->preCheck();

// Invoice Age - number of days - start
if ($invoice['owing'] > 0 ) {
    $invoiceAgeDays =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
    $invoiceAge = "$invoiceAgeDays {$LANG['days']}";
} else {
    $invoiceAge ="";
}

$urlForPdf = "index.php?module=export&amp;view=pdf&amp;id=" . $invoice['id'];

$invoice['url_for_pdf'] = $urlForPdf;

$customFieldLabels = CustomFields::getLabels(true);

$customFields = [];
for($idx=1; $idx<=4; $idx++) {
    $customFields[$idx] = CustomFields::showCustomField("invoice_cf{$idx}", $invoice["custom_field{$idx}"],
                                                          "read"           , 'details_screen summary'  ,
                                                          'details_screen' , 'details_screen'          ,
                                                          5                , ':');
}

$customerAccount = null;
$customerAccount['total'] = Customer::calcCustomerTotal($customer['id']);
$customerAccount['paid']  = Payment::calcCustomerPaid($customer['id']);
$customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

$smarty->assign("customFields"        , $customFields);
$smarty->assign("customFieldLabels"   , $customFieldLabels);
$smarty->assign("invoiceAge"          , $invoiceAge);
$smarty->assign("invoiceNumberOfTaxes", $invoiceNumberOfTaxes);
$smarty->assign("invoiceItems"        , $invoiceItems);
$smarty->assign("defaults"            , $defaults);
$smarty->assign("preference"          , $preference);
$smarty->assign("biller"              , $biller);
$smarty->assign("customer"            , $customer);
$smarty->assign("subCustomer"         , $subCustomer);
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
