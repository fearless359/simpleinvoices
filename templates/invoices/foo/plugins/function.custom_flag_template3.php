<?php

use Inc\Claz\CustomFlags;

/**
 * @param array $params
 * @param object $template
 * @noinspection PhpVariableNamingConventionInspection
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_custom_flag_template3(array $params, object &$template) {
    global $smarty;

    $pf = $params['pf'];
    $p  = $params['p'];

    for($idx = 1; $idx <= 4; $idx++) {
        $lbl1 = sprintf('product_cf%d', $idx);
        $lbl2 = sprintf('custom_field%d', $idx);
        if (!empty($p[$lbl2])) {
            if (!CustomFlags::isCustomFlagField($pf[$lbl1])) {
                $labels = '';
                if (isset($smarty->useIt) && $smarty->useIt) {
                    $labels = htmlspecialchars($pf[$lbl1], ENT_QUOTES, 'UTF-8') . ': ';
                }
                $labels .= htmlspecialchars($p[$lbl2], ENT_QUOTES, 'UTF-8');
                echo "<tr>";
                echo "  <td class='font2' >&nbsp;</td>";
                echo "  <td class='font2' style='margin-left:30px;' colspan='2'>$labels</td>";
                echo "  <td class='font2' >&nbsp;</td>";
                echo "</tr>";
            }
        }
    }
}
