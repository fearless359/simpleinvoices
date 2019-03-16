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
$lang = SystemDefaults::getDefaultLanguage();
$languages = getLanguageList();
foreach ($languages as $language) {
    if ($language->shortname == $lang) {
        $lang = $language->name;
        break;
    }
}
$smarty->assign("defaultLanguage"         , $lang);

$smarty->assign("defaults"                , SystemDefaults::loadValues());
$smarty->assign("defaultDelete"           , SystemDefaults::getDefaultDelete());
$smarty->assign("defaultExpense"          , SystemDefaults::getDefaultExpense());
$smarty->assign("defaultInventory"        , SystemDefaults::getDefaultInventory());
$smarty->assign("defaultLogging"          , SystemDefaults::getDefaultLogging());
$smarty->assign("defaultPasswordLower"    , SystemDefaults::getDefaultPasswordLower());
$smarty->assign("defaultPasswordMinLength", SystemDefaults::getDefaultPasswordMinLength());
$smarty->assign("defaultPasswordNumber"   , SystemDefaults::getDefaultPasswordNumber());
$smarty->assign("defaultPasswordSpecial"  , SystemDefaults::getDefaultPasswordSpecial());
$smarty->assign("defaultPasswordUpper"    , SystemDefaults::getDefaultPasswordUpper());
$smarty->assign("defaultProductAttributes", SystemDefaults::getDefaultProductAttributes());

$smarty->assign("defaultBiller"           , Biller::getDefaultBiller());
$smarty->assign("defaultCustomer"         , Customer::getDefaultCustomer());
$smarty->assign("defaultPaymentType"      , PaymentType::getDefaultPaymentType());
$smarty->assign("defaultPreference"       , Preferences::getDefaultPreference());
$smarty->assign("defaultTax"              , Taxes::getDefaultTax());

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
