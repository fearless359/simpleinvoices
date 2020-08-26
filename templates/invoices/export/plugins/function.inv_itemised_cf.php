<?php
use Inc\Claz\Util;

/**
 * Generate table detail tag line for custom field
 * @param array $params
 * @param object $smarty
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_inv_itemised_cf(array $params, object &$smarty): void
{
    if (isset($params['field'])) {
        echo "<td style='width;50%;'>" . Util::htmlsafe($params['label']) . ": " . Util::htmlsafe($params['field']) . "</td>";
    }
}

