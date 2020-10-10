<?php
global $smarty;

/**
 * @return string first of month date.
 */
function firstOfMonth() {
    return date ( "Y-m-d", strtotime ('first day of this month') );
}

/**
 * @return string end of month date.
 */
function lastOfMonth() {
    return date ( "Y-m-d", strtotime ( 'last day of this month') );
}

$startDate  = isset($_POST['startDate'] ) ? $_POST['startDate']: firstOfMonth();
$endDate    = isset($_POST['endDate']   ) ? $_POST['endDate']  : lastOfMonth ();

$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
