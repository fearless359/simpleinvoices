<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\CustomFields;
use Inc\Claz\DbField;
use Inc\Claz\Invoice;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Preferences;
use Inc\Claz\Product;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Taxes;
use Inc\Claz\Util;

/*
 *  Script: details.php
 *      invoice details page
 *
 *  Author:
 *      Marcel van Dorp.
 *
 *  Last modified
 *      2018-10-20 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

// See if we are generating this from a default_invoice template
$defaultTemplateSet = !empty($_GET['template']);
$masterInvoiceId = $defaultTemplateSet ? $_GET ['template'] : $_GET ['id'];

try {
    $invoice = Invoice::getOne($masterInvoiceId);
    if ($defaultTemplateSet) {
        $invoice['id'] = null;
    }

    $invoiceItems = Invoice::getInvoiceItems($masterInvoiceId);

    foreach ($invoiceItems as $key => $val) {
        // get list of attributes
        $prod = Product::getOne($val['product_id']);
        $jsonAtt = json_decode($prod['attribute']);
        if ($jsonAtt !== null) {
            $html = "<tr id='json_html{$key}'><td></td><td colspan='5'><table><tr>";
            foreach ($jsonAtt as $key2 => $val2) {
                if ($val2 == 'true') {
                    $pdoDb->setSelectList([
                        new DbField('a.name', 'name'),
                        'enabled',
                        'type',
                        new DbField('t.name')
                    ]);

                    $jn = new Join("LEFT", "products_attribute_type", "t");
                    $jn->addSimpleItem("a.type_id", new DbField("a.id"));
                    $pdoDb->addToJoins($jn);

                    $pdoDb->addSimpleWhere('a.id', $key2);

                    $rows = $pdoDb->request('SELECT', 'products_attributes', 'a');
                    $attrName = $rows[0];

                    $pdoDb->setSelectList([
                        new DbField('a.name', 'name'),
                        new DbField('v.id', 'v'),
                        new DbField('v.value', 'value'),
                        new DbField('v.enabled', 'enabled')
                    ]);

                    $jn = new Join("LEFT", "products_values", "v");
                    $jn->addSimpleItem("a.id", new DbField("v.attribute_id"));
                    $pdoDb->addToJoins($jn);

                    $pdoDb->addSimpleWhere('a.id', $key2);

                    $attrVals = $pdoDb->request('SELECT', 'products_attributes', 'a');

                    if ($attrName['enabled'] == ENABLED && $attrName['type'] == 'list') {
                        $html .= "<td>{$attrName['name']}<select name='attribute[{$key}][{$key2}]'>";
                        $html .= "<option value=''></option>";
                        foreach ($attrVals as $attrVal) {
                            if ($attrVal['enabled'] == ENABLED) {
                                $selected = "";
                                foreach ($val['attribute_decode'] as $aKey => $aVal) {
                                    if ($key2 == $aKey && $aVal == $attrVal['id']) {
                                        $selected = "selected";
                                        break;
                                    }
                                }
                                $html .= "<option {$selected} value='{$attrVal['id']}'>{$attrVal['value']}</option>";
                            }
                        }
                        $html .= "</select></td>";
                    }
                    if ($attrName['enabled'] == ENABLED && $attrName['type'] == 'free') {
                        $attributeValue = '';
                        foreach ($val['attribute_decode'] as $aKey => $aVal) {
                            if ($key2 == $aKey) {
                                $attributeValue = $aVal;
                                break;
                            }
                        }
                        $html .= "<td>{$attrName['name']}<input name='attribute[{$key}][{$key2}]'  value='{$attributeValue}' /></td>";
                    }
                    if ($attrName['enabled'] == ENABLED && $attrName['type'] == 'decimal') {
                        $attributeValue = '';
                        foreach ($val['attribute_decode'] as $aKey => $aVal) {
                            if ($key2 == $aKey) {
                                $attributeValue = $aVal;
                                break;
                            }
                        }
                        $html .= "<td>{$attrName['name']}<input name='attribute[{$key}][{$key2}]' size='5' value='{$attributeValue}' /></td>";
                    }
                }
            }
            $html .= "</tr></table></td></tr>";
            $invoiceItems[$key]['html'] = $html;
        }
    }

    $customFields = [];
    for ($idx = 1; $idx <= 4; $idx++) {
        $customFields[$idx] = CustomFields::showCustomField("invoice_cf{$idx}", $invoice["custom_field{$idx}"],
            "write", '', "details_screen",
            '', '', '');
    }
    // @formatter:off
    $smarty->assign ("invoice"     , $invoice);
    $smarty->assign ("invoiceItems", $invoiceItems);
    $smarty->assign ("lines"       , count ($invoiceItems));
    $smarty->assign ("customFields", $customFields);

    $smarty->assign ("billers"     , Biller::getAll(true));
    $smarty->assign ("customers"   , Customer::getAll(['enabledOnly' => true, 'incl_cust_id' => "{$invoice['customer_id']}"]));
    $smarty->assign ("defaults"    , SystemDefaults::loadValues());
    $smarty->assign ("preference"  , Preferences::getOne($invoice['preference_id']));
    $smarty->assign ("preferences" , Preferences::getActivePreferences());
    $smarty->assign ("products"    , Product::getAll(true));
    $smarty->assign ("taxes"       , Taxes::getAll());
    // @formatter:on
} catch (PdoDbException $pde) {
    error_log("modules/invoices/details.php: error " . $pde->getMessage());
    exit('Unable to complete request');
}

$smarty->assign('pageActive', 'invoice');
$smarty->assign('subPageActive', 'invoice_edit');
$smarty->assign('activeTab', '#money');

