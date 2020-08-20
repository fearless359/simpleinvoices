<?php

use Inc\Claz\Util;

function smartyFunctionsDoTr($params, &$smarty)
{
    if ($params['number'] == 2) {
        $newTr = "</tr><tr class='" . Util::htmlsafe($params['class']) . "'>";
        return $newTr;
    }

    if ($params['number'] == 4) {
        $newTr = "</tr><tr class='" . Util::htmlsafe($params['class']) . "'>";
        return $newTr;
    }

}

