<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\PaymentType;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// gets the long language name out of the short name
$lang = SystemDefaults::getLanguage();
$languages = getLanguageList();
foreach ($languages as $language) {
    if ($language->shortname == $lang) {
        $lang = $language->name;
        break;
    }
}
$smarty->assign("defaultLanguage", $lang);

$smarty->assign("defaults"                     , SystemDefaults::loadValues());
$smarty->assign("defaultDelete"                , SystemDefaults::getDelete());
$smarty->assign("defaultExpense"               , SystemDefaults::getExpense());
$smarty->assign("defaultInventory"             , SystemDefaults::getInventory());
$smarty->assign("defaultInvoiceDescriptionOpen", SystemDefaults::getInvoiceDescriptionOpen());
$smarty->assign("defaultLogging"               , SystemDefaults::getLogging());
$smarty->assign("defaultPasswordLower"         , SystemDefaults::getPasswordLower());
$smarty->assign("defaultPasswordMinLength"     , SystemDefaults::getPasswordMinLength());
$smarty->assign("defaultPasswordNumber"        , SystemDefaults::getPasswordNumber());
$smarty->assign("defaultPasswordSpecial"       , SystemDefaults::getPasswordSpecial());
$smarty->assign("defaultPasswordUpper"         , SystemDefaults::getPasswordUpper());
$smarty->assign("defaultProductAttributes"     , SystemDefaults::getProductAttributes());
$smarty->assign("defaultProductGroups"         , SystemDefaults::getProductGroups());
$smarty->assign("defaultSubCustomer"           , SystemDefaults::getSubCustomer());

$smarty->assign("defaultBiller"     , Biller::getDefaultBiller());
$smarty->assign("defaultCustomer"   , Customer::getDefaultCustomer());
$smarty->assign("defaultPaymentType", PaymentType::getDefaultPaymentType());
$smarty->assign("defaultPreference" , Preferences::getDefaultPreference());
$smarty->assign("defaultTax"        , Taxes::getDefaultTax());

$smarty->assign('pageActive', 'system_default');
$smarty->assign('activeTab', '#setting');
