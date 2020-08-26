<?php
use Inc\Claz\Util;

/**
 * Generate itemized invoice custom field table detail line.
 * @param array $params Contains associative array with the entries for key value, "label" and "field".
 */
function smarty_function_inv_itemised_cf(array $params)
{
    if ($params['field'] != null) {
        echo "<td style='width:50%;'>" . Util::htmlsafe($params['label']) . ": " . Util::htmlsafe($params['field']) . "</td>";
    }
}
