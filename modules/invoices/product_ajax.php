<?php

use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\SiLocal;
use Inc\Claz\Util;

global $pdoDb, $auth_session;

$row_id = Util::htmlsafe($_GET['row']);
$id = $_GET['id'];
if (!empty($id)) {
    $output = array();
    $rows = array();
    try {
        $pdoDb->addSimpleWhere("id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $auth_session->domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "products");
    } catch (PdoDbException $pde) {
        error_log("modules/invoices/product_ajax.php - error: " . $pde->getMessage());
    }

    if (!empty($rows)) {
        $row = $rows[0];
        $attr = (empty($row['attribute']) ? "[]" : $row['attribute']);
        $html = "";
        $json_att = json_decode($attr);
        if($json_att !== null && $attr !== '[]') {
            $html .= "<tr id='json_html$row_id'>";
            $html .= "  <td></td>";
            $html .= "  <td colspan='5'>";
            $html .= "    <table>";
            $html .= "      <tr>";
            foreach ($json_att as $k => $v) {
                if ($v == 'true') {
                    $attr_name = array('enabled' => DISABLED);
                    try {
                        $join = new Join("INNER", "products_attribute_type", "t");
                        $join->addSimpleItem("a.type_id", "t.id");
                        $pdoDb->addToJoins($join);
                        $pdoDb->addSimpleWhere("id", $k);
                        $pdoDb->setSelectList(array("name", "enabled", "t.name AS type"));
                        $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                        if (!empty($rows)) {
                            $attr_name = $rows[0];
                        }
                    } catch (PdoDbException $pde) {
                        error_log("modules/invoices/product_ajax.php - error(2): " . $pde->getMessage());
                    }

                    $rows = array();
                    try {
                        $join = new Join("INNER", "products_values", "v");
                        $join->addSimpleItem("a.id", "v.attribute_id");
                        $pdoDb->addToJoins($join);
                        $pdoDb->addSimpleWhere("a.id", $k);
                        $pdoDb->setSelectList(array("a.name AS name", "v.id AS id", "v.value AS value", "v.enabled AS enabled"));
                        $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                    } catch (PdoDbException $pde) {
                        error_log("modules/invoices/product_ajax.php - error(2): " . $pde->getMessage());
                    }
                    if ($attr_name['enabled'] == ENABLED) {
                        if ($attr_name['type'] == 'list') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "           <select name='attribute[$row_id][$k]'>";
                            foreach ($rows as $att_val) {
                                if ($att_val['enabled'] ==ENABLED) {
                                    $html .= "             <option value='" . $att_val['id'] . "'>{$att_val['value']}</option>";
                                }
                            }
                            $html .= "           </select>";
                            $html .= "         </td>";
                        } else if ($attr_name['type'] == 'free') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "          <input name='attribute[$row_id][$k]' />";
                            $html .= "        </td>";
                        } else if ($attr_name['type'] == 'decimal') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "          <input name='attribute[$row_id][$k]' size='5'/>";
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

        // Format with decimal places with precision as defined in config.php
        // @formatter:off
        $output['unit_price']           = SiLocal::number($row['unit_price']);
        $output['default_tax_id']       = (isset($row['default_tax_id']) ? $row['default_tax_id'] : "");
        $output['default_tax_id_2']     = (isset($row['default_tax_id_2']) ? $row['default_tax_id_2'] : "");
        $output['attribute']            = (isset($row['attribute']) ? $row['default_tax_id_2'] : "");
        $output['json_attribute']       = $json_att;
        $output['json_html']            = $html;
        $output['notes']                = (isset($row['notes']) ? $row['notes'] : "");
        $output['notes_as_description'] = (isset($row['notes_as_description']) ? $row['notes_as_description'] : "N");
        $output['show_description']     = (isset($row['show_description']) ? $row['show_description'] : "N");
        // @formatter:on
    } else {
        $output .= '';
    }

    echo json_encode($output);
    exit();
}
echo "";
