<?php
use Inc\Claz\Util;

function smarty_function_inv_itemised_cf($params, &$smarty)
{
    //$print_cf ="testsd";
    if ($params['field'] != null) {
        $print_cf .= "<td width=50%>" . Util::htmlSafe($params[label]) . ": " . Util::htmlSafe($params[field]) . "</td>";
        echo $print_cf;
    }
}

