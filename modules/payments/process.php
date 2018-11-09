<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\DomainId;
use Inc\Claz\Invoice;
use Inc\Claz\PaymentType;
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;

global $smarty, $LANG, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$paymentTypes = PaymentType::select_all(true);
$chk_pt = 0;
foreach ($paymentTypes as $paymentType) {
    if (preg_match('/^check$/iD', $paymentType['pt_description'])) {
        $chk_pt = trim($paymentType['pt_id']);
        break;
    }
}

// Generate form validation script
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateIfNum("ac_amount",$LANG['amount']);
jsValidateIfNum("ac_date",$LANG['date']);
echo "if(theForm.ac_payment_type.value=='$chk_pt') {\n";
echo "    var cknum = theForm.ac_check_number.value;\n";
echo "    cknum = cknum.toUpperCase();\n";
echo "    if(!(/^[1-9][0-9]* *$/).test(cknum) && cknum != 'N/A') {\n";
echo "        alert('Enter a valid Check Number, \"N/A\" or change the Payment Type.');\n";
echo "        theForm.ac_check_number.focus();\n";
echo "        return (false);\n";
echo "    };\n";
echo "    theForm.ac_check_number.value = cknum;\n";
echo "}\n";
jsFormValidationEnd();
jsEnd();
// end validation generation

$today = date("Y-m-d");

if(isset($_GET['id'])) {
    $invoice = Invoice::select($_GET['id']);
} else {
    $rows = array();
    try {
        $pdoDb->addSimpleWhere("domain_id", DomainId::get());
        $rows = $pdoDb->request("SELECT", "invoices");
    } catch (PdoDbException $pde) {
        error_log("modules/payments/process.php - error(1): " . $pde->getMessage());
    }
    $invoice = $rows[0];
}

// @formatter:off
$customer = Customer::get($invoice['customer_id']);
$biller   = Biller::select($invoice['biller_id']);
$defaults = SystemDefaults::loadValues();

// Presets value that will be used in the Invoice::select_all() method.
try {
    $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
} catch (PdoDbException $pde) {
    error_log("modules/payments/process.php - error(2): " . $pde->getMessage());
}
$invoice_all = Invoice::select_all("count", "id", "", null, "", "", "");

$smarty->assign('invoice_all',$invoice_all);

$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', "payment_process");
$smarty->assign('active_tab'   , '#money');
// @formatter:on
