<?php

use Inc\Claz\Util;

/**
 * Generate itemized invoice custom field table detail line.
 * @param array $params Contains associative array with the entries for key value, "label" and "field".
 *   label   - The name of the field, ie. Custom Field 1, Email, etc.
 *   field   - The actual value to be printed, ie. total, paid, owing, etc.
 */
function smarty_function_inv_itemized_cf(array $params): void
{
    if (!empty($params['field'])) {
        echo "<td class='half_width'><span class='semi_bold'>" . Util::htmlSafe($params['label']) . ":</span>&nbsp;" .
                 Util::htmlSafe($params['field']) .
             "</td>";
    }
}
