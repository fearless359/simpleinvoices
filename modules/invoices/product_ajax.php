<?php

use Inc\Claz\DbField;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Product;
use Inc\Claz\Util;

global $pdoDb;

session_name('SiAuth');
session_start();

$rowId = Util::htmlSafe($_GET['row']);
$id = $_GET['id'];
if (!empty($id)) {
    $output = [];

    $row = Product::getOne($id);
    if (!empty($row)) {
        $attr = empty($row['attribute']) ? "[]" : $row['attribute'];
        $html = "";
        $jsonAtt = json_decode($attr);
        if($jsonAtt !== null && $attr !== '[]') {
            $html .= "<tr id='json_html{$rowId}>";
            $html .= "  <td></td>";
            $html .= "  <td colspan='5'>";
            $html .= "    <table>";
            $html .= "      <tr>";
            foreach ($jsonAtt as $key => $val) {
                if ($val == 'true') {
                    $attrName = ['enabled' => DISABLED];
                    try {
                        $join = new Join("INNER", "products_attribute_type", "t");
                        $join->addSimpleItem("a.type_id", "t.id");
                        $pdoDb->addToJoins($join);
                        $pdoDb->addSimpleWhere("a.id", $key);
                        $pdoDb->setSelectList(["name", "enabled", new DbField("t.name", "type")]);
                        $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                        if (!empty($rows)) {
                            $attrName = $rows[0];
                        }
                    } catch (PdoDbException $pde) {
                        error_log("modules/invoices/product_ajax.php - error(2): " . $pde->getMessage());
                    }

                    $rows = [];
                    try {
                        $join = new Join("INNER", "products_attributes_values", "v");
                        $join->addSimpleItem("a.id", "v.attribute_id");
                        $pdoDb->addToJoins($join);
                        $pdoDb->addSimpleWhere("a.id", $key);
                        $pdoDb->setSelectList([
                            new DbField("a.name", "name"),
                            new DbField("v.id", "id"),
                            new DbField("v.value", "value"),
                            new DbField("v.enabled", "enabled")
                        ]);
                        $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                    } catch (PdoDbException $pde) {
                        error_log("modules/invoices/product_ajax.php - error(2): " . $pde->getMessage());
                    }
                    if ($attrName['enabled'] == ENABLED) {
                        if ($attrName['type'] == 'list') {
                            $html .= "        <td>" . $attrName['name'];
                            $html .= "           <select name='attribute[$rowId][$key]'>";
                            foreach ($rows as $attVal) {
                                if ($attVal['enabled'] ==ENABLED) {
                                    $html .= "             <option value='" . $attVal['id'] . "'>{$attVal['value']}</option>";
                                }
                            }
                            $html .= "           </select>";
                            $html .= "         </td>";
                        } elseif ($attrName['type'] == 'free') {
                            $html .= "        <td>" . $attrName['name'];
                            $html .= "          <input name='attribute[$rowId][$key]' />";
                            $html .= "        </td>";
                        } elseif ($attrName['type'] == 'decimal') {
                            $html .= "        <td>" . $attrName['name'];
                            $html .= "          <input name='attribute[$rowId][$key]' size='5'/>";
                            $html .= "        </td>";
                        }
                    }
                }
            }
            $html .= "      </tr>";
            $html .= "    </table>";
            $html .= "  </td>";
            $html .= "</tr>";
        }

        // Format with decimal places with precision as defined in config.ini
        // @formatter:off
        $output['unit_price']           = Util::number($row['unit_price']);
        $output['markup_price']         = Util::number($row['markup_price']);
        $output['default_tax_id']       = isset($row['default_tax_id']) ? $row['default_tax_id'] : "";
        $output['default_tax_id_2']     = isset($row['default_tax_id_2']) ? $row['default_tax_id_2'] : "";
        $output['attribute']            = isset($row['attribute']) ? $row['default_tax_id_2'] : "";
        $output['json_attribute']       = $jsonAtt;
        $output['json_html']            = $html;
        $output['notes']                = isset($row['notes']) ? $row['notes'] : "";
        $output['notes_as_description'] = isset($row['notes_as_description']) ? $row['notes_as_description'] : "N";
        $output['show_description']     = isset($row['show_description']) ? $row['show_description'] : "N";
        // @formatter:on
    } else {
        $output .= '';
    }

    echo json_encode($output);
    exit();
}
echo "";
