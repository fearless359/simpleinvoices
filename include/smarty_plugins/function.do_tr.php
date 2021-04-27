<?php
/**
 * @param array $params
 * @return string|void
 */
function smarty_function_do_tr(array $params)
{
    if ($params['number'] == 2 || $params['number'] == 4) {
        return "</tr><tr class='{$params['class']}'>";
    }
    return;
}
