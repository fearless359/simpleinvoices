<?php

use Inc\Claz\Util;

function smarty_function_do_tr($params, &$smarty)
{
    if ($params['number'] == 2) {
        $newTr = "</tr><tr class='" . Util::htmlSafe($params['class']) . "'>";
        return $newTr;
    }

    if ($params['number'] == 4) {
        $newTr = "</tr><tr class='" . Util::htmlSafe($params['class']) . "'>";
        return $newTr;
    }

}

