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
$default_template_set = (!empty($_GET['template']));
$master_invoice_id = ($default_template_set ? $_GET ['template'] : $_GET ['id']);
$invoice = Invoice::getInvoice($master_invoice_id);
if ($default_template_set) {
    $invoice['id'] = null;
}
// @formatter:off
$invoiceItems = Invoice::getInvoiceItems ( $master_invoice_id );
$customers    = Customer::get_all(true, $invoice['customer_id']);
$preference   = Preferences::getPreference( $invoice ['preference_id'] );
$billers      = Biller::getAll(true);
$defaults     = SystemDefaults::loadValues();
$taxes        = Taxes::getTaxes ();
$preferences  = Preferences::getActivePreferences ();
$products     = Product::select_all ();

$customFields = array ();
for ($i = 1; $i <= 4; $i++) {
    $customFields[$i] = CustomFields::showCustomField("invoice_cf$i", $invoice["custom_field$i"],
                                                        "write", '',
                                                        "details_screen", '',
                                                        '', '');
}

foreach ($invoiceItems as $key => $value) {
    // get list of attributes
    $prod = Product::select ($value['product_id']);
    $json_att = json_decode ($prod['attribute']);
    if ($json_att !== null) {
        $html = "<tr id='json_html{$key}'><td></td><td colspan='5'><table><tr>";
        foreach ($json_att as $k => $v) {
            if ($v == 'true') {
                try {
                    $pdoDb->setSelectList(array(
                        new DbField('a.name', 'name'),
                        'enabled',
                        'type',
                        new DbField('t.name')
                    ));

                    $jn = new Join("LEFT", "products_attribute_type", "t");
                    $jn->addSimpleItem("a.type_id", new DbField("a.id"));
                    $pdoDb->addToJoins($jn);

                    $pdoDb->addSimpleWhere('a.id', $k);

                    $rows = $pdoDb->request('SELECT', 'products_attributes', 'a');
                    $attr_name = $rows[0];

                    $pdoDb->setSelectList(array(
                        new DbField('a.name', 'name'),
                        new DbField('v.id', 'v'),
                        new DbField('v.value', 'value'),
                        new DbField('v.enabled', 'enabled')
                    ));

                    $jn = new Join("LEFT", "products_values", "v");
                    $jn->addSimpleItem("a.id", new DbField("v.attribute_id"));
                    $pdoDb->addToJoins($jn);

                    $pdoDb->addSimpleWhere('a.id', $k);

                    $att_vals = $pdoDb->request('SELECT', 'products_attributes', 'a');

                    if ($attr_name['enabled'] == ENABLED && $attr_name['type'] == 'list') {
                        $html .= "<td>{$attr_name['name']}<select name='attribute[{$key}][{$k}]'>";
                        $html .= "<option value=''></option>";
                        foreach ($att_vals as $att_val) {
                            if ($att_val['enabled'] == ENABLED) {
                                $selected = "";
                                foreach ($value['attribute_decode'] as $a_key => $a_value) {
                                    if ($k == $a_key && $a_value == $att_val['id']) {
                                        $selected = "selected";
                                        break;
                                    }
                                }
                                $html .= "<option {$selected} value='{$att_val['id']}'>{$att_val['value']}</option>";
                            }
                        }
                        $html .= "</select></td>";
                    }
                    if ($attr_name['enabled'] == ENABLED && $attr_name['type'] == 'free') {
                        $attribute_value = '';
                        foreach ($value['attribute_decode'] as $a_key => $a_value) {
                            if ($k == $a_key) {
                                $attribute_value = $a_value;
                                break;
                            }
                        }
                        $html .= "<td>{$attr_name['name']}<input name='attribute[{$key}][{$k}]'  value='{$attribute_value}' /></td>";
                    }
                    if ($attr_name['enabled'] == ENABLED && $attr_name['type'] == 'decimal') {
                        $attribute_value = '';
                        foreach ($value['attribute_decode'] as $a_key => $a_value) {
                            if ($k == $a_key) {
                                $attribute_value = $a_value;
                                break;
                            }
                        }
                        $html .= "<td>{$attr_name['name']}<input name='attribute[{$key}][{$k}]' size='5' value='{$attribute_value}' /></td>";
                    }
                } catch (PdoDbException $pde) {
                    error_log("modules/invoices/details.php: error " . $pde->getMessage());
                    exit('Unable to complete request');
                }
            }
        }
        $html .= "</tr></table></td></tr>";
        $invoiceItems[$key]['html'] = $html;
    }
}

$smarty->assign ("invoice"     , $invoice);
$smarty->assign ("defaults"    , $defaults);
$smarty->assign ("invoiceItems", $invoiceItems);
$smarty->assign ("customers"   , $customers);
$smarty->assign ("preference"  , $preference);
$smarty->assign ("billers"     , $billers);
$smarty->assign ("taxes"       , $taxes);
$smarty->assign ("preferences" , $preferences);
$smarty->assign ("products"    , $products);
$smarty->assign ("customFields", $customFields);
$smarty->assign ("lines"       , count ($invoiceItems));

$smarty->assign ('pageActive'   , 'invoice');
$smarty->assign ('subPageActive', 'invoice_edit');
$smarty->assign ('active_tab'   , '#money');
// @formatter:on
