<?php
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

$startDate  = isset($_POST['start_date'] ) ? $_POST['start_date']: firstOfMonth();
$endDate    = isset($_POST['end_date']   ) ? $_POST['end_date']  : lastOfMonth ();
