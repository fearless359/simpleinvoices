<?php

use Inc\Claz\Util;

function smarty_function_do_tr($params, &$smarty)
{
    if ($params['number'] == 2) {
        $new_tr = "</tr><tr class='" . Util::htmlsafe($params['class']) . "'>";
        return $new_tr;
    }

    if ($params['number'] == 4) {
        $new_tr = "</tr><tr class='" . Util::htmlsafe($params['class']) . "'>";
        return $new_tr;
    }

}

