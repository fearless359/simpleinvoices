<?php

use Inc\Claz\Customer;
use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\Payment;
use Inc\Claz\PdoDbException;

/*
 * Script: details.php
 * 	Customers details page
 *
 * Authors:
 *	 Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	    2016-10-21
 *
 * License:
 *	    GPL v3 or above
 *
 * Website:
 * 	https://simpleinvoices.group
 */
global $smarty, $LANG, $config;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateIfEmail("email", "true");
jsFormValidationEnd();
jsEnd();

// @formatter:off
$cid = $_GET['id'];
$domain_id = DomainId::get();

$customer = Customer::get($cid);
$customer['wording_for_enabled'] = ($customer['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
if (empty($customer['credit_card_number'])) {
    $customer['credit_card_number_masked'] = "";
} else {
    try {
        $key = $config->encryption->default->key;
        $enc = new \Encryption();
        $credit_card_number = $enc->decrypt($key, $customer['credit_card_number']);
        $customer['credit_card_number_masked'] = maskValue($credit_card_number);
    } catch (\Exception $e) {
        throw new \Exception("details.php - Unable to decrypt credit card for Customer, $cid. " . $e->getMessage());
    }
}
$invoices = Customer::getCustomerInvoices($cid);

$customer['total'] = Customer::calc_customer_total($customer['id'], true);
$customer['paid']  = Payment::calc_customer_paid( $customer['id'], true);
$customer['owing'] = $customer['total'] - $customer['paid'];

$customFieldLabel = getCustomFieldLabels(true);

$dir    =  "DESC";
$sort   =  "id";
$rp     = (isset($_POST['rp'])       ? $_POST['rp']       : "25");
$page   = (isset($_POST['page'])     ? $_POST['page']     : "1");
$query  = (isset($_REQUEST['query']) ? $_REQUEST['query'] : "");
$qtype  = (isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : "");

try {
    $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
} catch (PdoDbException $pde) {
    error_log("modules/customers/details.php - Unable to set Havings - error: " . $pde->getMessage());
}

//$invoices_owing = Invoice::select_all($type, $sort, $dir, $rp, $page, $query, $qtype);
$invoices_owing = Invoice::select_all("", $sort, $dir, $rp, $page, $query, $qtype);
$subPageActive  = ($_GET['action'] == "view"  ? "customer_view" : "customer_edit");

$smarty->assign('customer'        , $customer);
$smarty->assign('invoices'        , $invoices);
$smarty->assign('invoices_owing'  , $invoices_owing);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('pageActive'      , 'customer');
$smarty->assign('subPageActive'   , $subPageActive);
$smarty->assign('active_tab'      , '#people');
// @formatter:on
