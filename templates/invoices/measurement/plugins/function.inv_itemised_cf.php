<?php

use Inc\Claz\Util;

/**
 * Generate custom field table detail line for itemised invoice.
 * @param array $params
 * @param object $smarty
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_inv_itemised_cf(array $params, object &$smarty): void
{
    if ($params['field'] != null) {
        echo "<td style='width:50%;'>" . Util::htmlSafe($params['label']) . ": " . Util::htmlSafe($params['field']) . "</td>";
    }
}
