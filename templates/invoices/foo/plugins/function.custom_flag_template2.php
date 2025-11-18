<?php

/**
 * Additional setup for custom flag template
 * @param array $params
 * @param object $template
 * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
 */
function smarty_function_custom_flag_template2(array $params, object &$template) {
    $custCnt = $params['cust_cnt'];
    $cust    = $params['cust'];
    $ndx     = $params['ndx'];

    $custCnt--;
    while($custCnt > 0) {
        $custCnt--;
        echo '<tr>';
        echo '  <td class="font2" style="padding-left:6px;" colspan="3">';
        echo '    ' . $cust[$ndx++];
        echo '  </td>';
        echo '</tr>';
    }
    $template->assign('cust_cnt', $custCnt);
    $template->assign('ndx'     , $ndx);
}