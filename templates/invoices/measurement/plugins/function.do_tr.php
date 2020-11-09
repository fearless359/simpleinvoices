<?php

/**
 * @param array $params
 * @param object $smarty
 * @return string|void
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_do_tr(array $params, object &$smarty)
{
    if ($params['number'] == 2) {
        return "</tr><tr class='$params[class]'>";
    }

    if ($params['number'] == 4) {
        return "</tr><tr class='$params[class]'>";
    }

}
