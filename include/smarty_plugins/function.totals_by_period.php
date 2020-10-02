<?php
/** @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection */
/**
 * Updated template object.
 * @param array $params associative array with the key/value pair of "type".
 * @param object $template
 */
function smarty_function_totals_by_period(array $params, object &$template) {
    $data = $template->getTemplateVars('data');
    $template->assign('thisData', $data[$params['type']]);
}
