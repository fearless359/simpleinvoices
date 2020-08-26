<?php
/** @noinspection PhpRedundantDocCommentInspection */

use Inc\Claz\Util;

/**
 * @param $params
 * @param $smarty
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_inv_itemised_cf($params, &$smarty)
{
        if ($params['field'] != null) {
                echo "<td style='width:50%;'>" . Util::htmlsafe($params['label']) . ": " . Util::htmlsafe($params['field']) . "</td>";
        }
}

