<?php

use Inc\Claz\Biller;
use Inc\Claz\Cron;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/*
 * Script: renderQuickView.php
 *   Render quick view of invoice for this cron.
 *
 * Authors:
 *   Richard Rowley
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 */
global $config, $LANG, $smarty;

Util::directAccessAllowed();

$cronId = intval($_GET['cronId']);
try {
    // @formatter:off
    $invoice              = Cron::getCronInvoice($cronId);

    $invoiceId            = $invoice['id'];
    $domainId             = $invoice['domain_id'];
    $invoice['index_id']  = "Rendered from {$invoice['index_id']}";
    $invoice['date']      = "TBD";
    $invoice['paid']      = 0;
    $invoiceNumberOfTaxes = Invoice::numberOfTaxesForInvoice($invoiceId) + Cron::numberOfTaxesForInvoice($cronId);
    $customer             = Customer::getOne($invoice['customer_id']);
    $preference           = Preferences::getOne($invoice['preference_id']);

    $invoiceItems         = array_merge(Invoice::getInvoiceItems($invoiceId), Cron::getCronInvoiceItems($cronId, $domainId));

    $customFieldLabels    = CustomFields::getLabels(true);
    $customFields         = [];
    for ($idx = 1; $idx <= 4; $idx++) {
        $customFields[$idx] = CustomFields::showCustomField("invoice_cf$idx", $invoice["custom_field$idx"], "read");
    }

    //Customer accounts sections
    $customerAccount = [];
    $customerAccount['total'] = Customer::calcCustomerTotal($customer['id'], true);
    $customerAccount['paid']  = Payment::calcCustomerPaid($customer['id'], true);
    $customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];

    $smarty->assign("cronId"              , $cronId);
    $smarty->assign("customFields"        , $customFields);
    $smarty->assign("customFieldLabels"   , $customFieldLabels);
    $smarty->assign("invoiceAge"          , "");
    $smarty->assign("invoiceNumberOfTaxes", $invoiceNumberOfTaxes);
    $smarty->assign("invoiceItems"        , $invoiceItems);
    $smarty->assign("defaults"            , SystemDefaults::loadValues());
    $smarty->assign("preference"          , $preference);
    $smarty->assign("locale"              , $preference['locale']);
    $smarty->assign("currencyCode"        , $preference['currency_code']);
    $smarty->assign("biller"              , Biller::getOne($invoice['biller_id']));
    $smarty->assign("customer"            , $customer);
    $smarty->assign("invoiceType"         , Invoice::getInvoiceType($invoice['type_id']));
    $smarty->assign("invoice"             , $invoice);

    $smarty->assign('pageActive'          , 'cron');
    $smarty->assign('subPageActive'       , 'cronRenderInvoice');
    $smarty->assign('activeTab'           , '#money');
    // @formatter:on
} catch (PdoDbException $pde) {
    error_log("modules/cron/renderQuickView.php Exception: {$pde->getMessage()}");
    exit("Unable to process request. See error log for details.");
}
