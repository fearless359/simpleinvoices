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
use Inc\Claz\Util;

/*
 * Script: quickView.php
 * Quick view model
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *   2021-06-17 by Rich Rowley
 *
 * License:
 *   GPL v3 or above
 *     
 * Website:
 *   https://simpleinvoices.group */
global $config, $LANG, $smarty;

Util::directAccessAllowed();

$invoiceId = intval($_GET['id']);
try {
    // @formatter:off
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
    // @formatter:on

    //Invoice Age - number of days
    if ($invoice['owing'] > 0) {
        $invoiceAgeDays = $invoice['age_days'];
        $invoiceAge = "$invoiceAgeDays {$LANG['days']}";
    } else {
        $invoiceAge = "";
    }

    $urlForPdf = "index.php?module=export&amp;view=pdf&id=" . $invoice['id'];

    $invoice['url_for_pdf'] = $urlForPdf;

    $customFieldLabels = CustomFields::getLabels(true);

    $customFields = [];
    for ($idx = 1; $idx <= 4; $idx++) {
        $customFields[$idx] = CustomFields::showCustomField("invoice_cf$idx", $invoice["custom_field$idx"], "read");
    }

//    $attributes = ProductAttributes::getAll();

    //Customer accounts sections
    $customerAccount = [];
    $customerAccount['total'] = Customer::calcCustomerTotal($customer['id'], true);
    $customerAccount['paid'] = Payment::calcCustomerPaid($customer['id'], true);
    $customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

    // @formatter:off
//    $smarty->assign("attributes"          , $attributes);
    $smarty->assign("customFields"        , $customFields);
    $smarty->assign("customFieldLabels"   , $customFieldLabels);
    $smarty->assign("invoiceAge"          , $invoiceAge);
    $smarty->assign("invoiceNumberOfTaxes", $invoiceNumberOfTaxes);
    $smarty->assign("invoiceItems"        , $invoiceItems);
    $smarty->assign("defaults"            , $defaults);
    $smarty->assign("preference"          , $preference);
    $smarty->assign("locale"              , $preference['locale']);
    $smarty->assign("currencyCode"        , $preference['currency_code']);
    $smarty->assign("biller"              , $biller);
    $smarty->assign("customer"            , $customer);
    $smarty->assign("invoiceType"         , $invoiceType);
    $smarty->assign("invoice"             , $invoice);
    $smarty->assign("wordprocessor"       , $config['exportWordProcessor']);
    $smarty->assign("spreadsheet"         , $config['exportSpreadsheet']);
    $smarty->assign("customerAccount"     , $customerAccount);
    $smarty->assign("ewayPreCheck"        , $ewayPreCheck);

    $smarty->assign('pageActive', 'invoice');
    $smarty->assign('subPageActive', 'invoiceView');
    $smarty->assign('activeTab', '#money');
    // @formatter:on
} catch (PdoDbException $pde) {

}
