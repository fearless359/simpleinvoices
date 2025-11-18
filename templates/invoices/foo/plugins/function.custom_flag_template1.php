<?php

/**
 * Setup for custom flag in template
 * @param array $params
 * @param object $template
 * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
 */
function smarty_function_custom_flag_template1(array $params, object &$template) {
    $custCnt = $params['cust_cnt'];
    $custCnt--;
    $ndx= $params['ndx'];
    $cust = $params['cust'];
    echo '<td class="font2" style="padding-left:6px;">'.($custCnt > 0 ? $cust[$ndx++] : '').'</td>';
    $template->assign('cust_cnt', $custCnt);
    $template->assign('ndx'     , $ndx);
}
