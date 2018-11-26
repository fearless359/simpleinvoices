<?php
use Inc\Claz\Util;

function smarty_function_inv_itemised_cf($params)
{
    if ($params['field'] != null) {
        echo "<td width=50%>" . Util::htmlsafe($params[label]) . ": " . Util::htmlsafe($params[field]) . "</td>";
    }
}
