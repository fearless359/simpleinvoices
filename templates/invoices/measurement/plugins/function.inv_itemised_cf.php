<?php

use Inc\Claz\Util;

function smarty_function_inv_itemised_cf($params, &$smarty)
{
    if ($params['field'] != null) {
        $print_cf .= "<td width=50%>" . Util::htmlsafe($params[label]) . ": " . Util::htmlsafe($params[field]) . "</td>";
        echo $print_cf;
    }
}
