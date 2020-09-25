<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\PaymentType;
use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

global $LANG,
       $smarty,
       $extensionPhpInsertFiles,
       $performExtensionPhpInsertions;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

/**
 * Help function for sorting the language array by name
 * @param object $aVal
 * @param object $bVal
 * @return int 1 if $aVal is greater than $bVal; otherwise -1.
 */
function compareNameIndex($aVal, $bVal)
{
    $aResult = $aVal->name . "";
    $bResult = $bVal->name . "";
    return $aResult > $bResult ? 1 : -1;
}

$defaults = SystemDefaults::loadValues();
$value = '';
$description = "{$LANG['noDefaults']}";
$default = null;

$getVal = empty($_GET['submit']) ? '' : trim($_GET['submit']);
switch ($getVal) {
    case "biller":
        $default = "biller";
        $billers = Biller::getAll(true);
        if (empty($billers)) {
            $value = "<p><em>{$LANG['noBillers']}</em></p>\n";
        } else {
            $value  = "<select name='value'>\n";
            $value .= "  <option value='0'></option>\n";

            foreach ($billers as $biller) {
                $selected = $biller['id'] == $defaults['biller'] ? "selected style='font-weight: bold'" : "";
                $escaped  = Util::htmlSafe($biller['name']);
                $value   .= "<option {$selected} value='{$biller['id']}'>{$escaped}</option>\n";
            }
            $value .= "</select>\n";
        }

        $description = "{$LANG['billerName']}";
        break;

    case "company_logo":
        $default     = $getVal;
        $description = "{$LANG[$default]}";
        $attribute   = Util::htmlSafe($defaults[$default]);
        $escaped = Util::htmlSafe($defaults['template']);
        $fileList = glob('templates/invoices/logos/*');
        $value = "<select name=\"value\">\n";
        $value .= "  <option value=' '></option>\n";
        foreach ($fileList as $file) {
            $parts = explode("/", $file);
            $filename = $parts[count($parts) - 1];
            $selected = $filename == $attribute ? "selected" : "";
            $value .= "  <option value='{$filename}' {$selected}>{$filename}</option>\n";
        }
        $value .= "</select>\n";

        $found       = true;
        break;

    case "company_name_item":
        $default     = $getVal;
        $description = "{$LANG['companyNameItemLabel']}";
        $attribute   = Util::htmlSafe($defaults[$default]);
        $value       = "<input type='text' size='60' name='value' value='{$attribute}' required />\n";
        $found       = true;
        break;

    case "customer":
        $default = "customer";
        $customers = Customer::getAll(['enabled_only' => true]);

        if (empty($customers)) {
            $value  = "<p><em>{$LANG['noCustomers']}</em></p>" . "\n";
        } else {
            $value  = "<select name='value'>\n";
            $value .= "  <option value='0'> </option>\n";

            foreach ($customers as $customer) {
                $selected = $customer['id'] == $defaults['customer'] ? "selected style='font-weight: bold'" : "";
                $escaped  = Util::htmlSafe($customer['name']);
                $value   .= "<option {$selected} value='{$customer['id']}'>{$escaped}</option>\n";
            }
            $value .= "</select>\n";
        }

        $description = "{$LANG['customerName']}";
        break;

    case "def_inv_template":
        $default = "template";

        /****************************************************************
         * Make drop down list invoice template - start
         * Note: Only show the folder names in src/invoices/templates
         ****************************************************************/
        $handle = opendir("templates/invoices/");
        $files = [];
        while ($template = readdir($handle)) {
            if ($template != ".." &&
                $template != "." &&
                $template != "logos" &&
                $template != ".svn" &&
                $template != "template.php" &&
                $template != "template.php~") {
                $files[] = $template;
            }
        }
        closedir($handle);
        sort($files);
        $escaped = Util::htmlSafe($defaults['template']);
        $value = "<select name=\"value\">\n";
        $value .= "  <option selected value='{$escaped}' style='font-weight:bold;'>{$escaped}</option>\n";
        foreach ($files as $var) {
            $var = Util::htmlSafe($var);
            $value .= "  <option value='{$var}'>{$var}</option>\n";
        }
        $value .= "</select>\n";
        /****************************************************************
         * Make drop down list invoice template - end
         ****************************************************************/

        $description = $LANG['defaultInvTemplate'];
        break;

    case "def_payment_type":
        $payments = PaymentType::getAll(true);
        if (empty($payments)) {
            $value = "<p><em>{$LANG['paymentType']}</em></p>";
        } else {
            $default = "payment_type";
            $value   = "<select name='value'>\n";
            $value  .= "  <option value='0'> </option>\n";

            foreach ($payments as $payment) {
                $selected = $payment['pt_id'] == $defaults['payment_type'] ? " selected style='font-weight: bold'" : "";
                $escaped = Util::htmlSafe($payment['pt_description']);
                $value .= "  <option {$selected} value='{$payment['pt_id']}'>\n";
                $value .= "    {$escaped}\n";
                $value .= "  </option>\n";
            }
        }

        $description = "{$LANG['paymentType']}";
        break;

    case "default_invoice":
        $default = "default_invoice";
        $value = "<input type='text' size='10' name='value' class='validate[required,custom[number]' value='" .
                         Util::htmlSafe($defaults['default_invoice']) . "'>";
        $description = "{$LANG['defaultInvoice']}";
        break;

    case "delete":
        $array = [
            0 => $LANG['disabled'],
            1 => $LANG['enabled']
        ];
        $default     = "delete";
        $description = $LANG['delete'];
        $value       = Util::dropDown($array, $defaults['delete']);
        break;

    case "expense":
        $array = [
            0 => $LANG['disabled'],
            1 => $LANG['enabled']
        ];
        $default     = "expense";
        $description = $LANG['expenseUc'];
        $value       = Util::dropDown($array, $defaults[$default]);
        break;

    case "inventory":
        $array = [
            0 => $LANG['disabled'],
            1 => $LANG['enabled']
        ];
        $default     = "inventory";
        $description = $LANG['inventory'];
        $value       = Util::dropDown($array, $defaults[$default]);
        break;

    case "language":
        $default   = "language";
        $languages = getLanguageList();
        $lang      = SystemDefaults::getLanguage();

        usort($languages, "compareNameIndex");
        $description  = $LANG['language'];
        $value        = "<select name='value'>";
        foreach ($languages as $language) {
            $selected = $language->shortname == $lang ? " selected" : '';
            $value   .= "  <option {$selected} value='" . Util::htmlSafe($language->shortname) . "'>\n";
            $value   .= "    " . Util::htmlSafe("$language->name ($language->englishname) ($language->shortname)") . "\n";
            $value   .= "  </option>\n";
        }
        $value       .= "</select>\n";
        break;

    case "line_items":
        $default = "line_items";
        $value = "<input type='text' size='25' name='value' class='validate[required,custom[number]' value='" .
                         Util::htmlSafe($defaults['line_items']) . "'>";
        $description = "{$LANG['defaultNumberItems']}";
        break;

    case "logging":
        $array = [
            0 => $LANG['disabled'],
            1 => $LANG['enabled']
        ];
        $default     = "logging";
        $description = $LANG['logging'];
        $value       = Util::dropDown($array, $defaults[$default]);
        break;

    case 'password_min_length':
        $default     = $getVal;
        $description = "{$LANG[$default]}";
        $attribute   = Util::htmlSafe($defaults[$default]);
        $value       = "<input type='text' size='2' name='value' value='$attribute' required min='6' max='16' />\n";
        $found       = true;
        break;

    case 'password_lower':
    case 'password_number':
    case 'password_special':
    case 'password_upper':
        $default     = $getVal;
        $description = "{$LANG[$default]}";
        $array = [
            0 => $LANG['disabled'],
            1 => $LANG['enabled']
        ];
        $value = Util::dropDown($array, $defaults[$default]);
        $found = true;
        break;

    case "preference_id":
        $preferences = Preferences::getActivePreferences();

        if (empty($preferences)) {
            $value = "<p><em>{$LANG['noPreferences']}</em></p>\n";
        } else {
            $default = "preference";
            $value   = "<select name='value'>\n";
            $value  .= "  <option value='0'></option>\n";

            foreach ($preferences as $preference) {
                $selected = $preference['pref_id'] == $defaults['preference'] ? ' selected style="font-weight:bold"' : '';
                $escaped = Util::htmlSafe($preference['pref_description']);
                $value .= "  <option {$selected} value='{$preference['pref_id']}'>\n";
                $value .= "    {$escaped}\n";
                $value .= "  </option>\n";
            }
        }

        $description = "{$LANG['invPref']}";
        break;

    case "product_attributes":
        $array = [
            DISABLED => $LANG['disabled'],
            ENABLED => $LANG['enabled']
        ];
        $default     = "product_attributes";
        $description = $LANG['productAttributes'];
        $value       = Util::dropDown($array, $defaults[$default]);
        break;

    case "session_timeout":
        // The $description, $default, $value fields are required to set up the generic
        // edit template for this extension value.
        $default     = $getVal;
        $description = "{$LANG[$default]}";
        $attribute   = Util::htmlSafe($defaults[$default]);
        $value       = "<input type='text' size='4' name='value' value='{$attribute}' min='15' max='999' />\n";
        $found       = true;
        break;

    case "sub_customer":
        $array = [
            DISABLED => $LANG['disabled'],
            ENABLED => $LANG['enabled']
        ];
        $default     = "sub_customer";
        $description = $LANG['subCustomer'];
        $value       = Util::dropDown($array, $defaults[$default]);
        break;

    case "tax":
        $default = "tax";
        $taxes = Taxes::getActiveTaxes();
        if (empty($taxes)) {
            $value = "<p><em>{$LANG['noTaxRates']}</em></p>\n";
        } else {
            $value = "<select name='value'>\n";
            $value .= "  <option value='0'> </option>\n";

            foreach ($taxes as $tax) {
                $selected = $tax['tax_id'] == $defaults['tax'] ? "selected style='font-weight: bold'" : "";
                $escaped = Util::htmlSafe($tax['tax_description']);
                $value .= "<option {$selected} value='{$tax['tax_id']}'>{$escaped}</option>\n";
            }
        }

        $description = "{$LANG['tax']}";
        break;

    case "tax_per_line_item":
        $default = "tax_per_line_item";
        $value = "<input type='text' size='25' name='value' value='" . Util::htmlSafe($defaults['tax_per_line_item']) . "'>\n";
        $description = "{$LANG['numberOfTaxesPerLineItem']}";
        break;

    default:
        // The following logic allows the edit of system default extension
        // values.  The content of the extension edit.tpl file will be inserted
        // loaded below and all the generic edit template to display them.
        // The $getVal variable contains the field name that is to be edited.
        $found = false;
        if ($performExtensionPhpInsertions) {
            foreach ($extensionPhpInsertFiles as $phpFile) {
                if ($phpFile['module'] == 'system_defaults' &&
                    $phpFile['view'] == 'edit') {
                    include_once $phpFile['file'];
                    if ($found) {
                        break;
                    }
                }
            }
        }

        if (!$found) {
            $default = null;
            $value = '';
            $description = "{$LANG['noDefaults']}";
        }
        break;
}

$smarty->assign('defaults', $defaults);
$smarty->assign('value', $value);
$smarty->assign('description', $description);
$smarty->assign('default', $default);
$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
