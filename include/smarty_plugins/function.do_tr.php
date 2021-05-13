<?php
/**
 * @param array $params
 * @return string|void
 * @noinspection PhpUnused
 */
function smarty_function_do_tr(array $params)
{
    if ($params['number'] == 2 || $params['number'] == 4) {
        if (!empty($params['class'])) {
            $cls = " class='{$params['class']}'";
        } else {
            $cls = '';
        }
        return "</tr><tr$cls>";
    }
    return;
}
