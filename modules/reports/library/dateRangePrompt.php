<?php
global $smarty;

/**
 * @return string first of month date.
 */
function firstOfMonth(): string
{
    return date ( "Y-m-d", strtotime ('first day of this month') );
}

/**
 * @return string end of month date.
 */
function lastOfMonth(): string
{
    return date ( "Y-m-d", strtotime ( 'last day of this month') );
}

$startDate  = $_POST['startDate'] ?? firstOfMonth();
$endDate    = $_POST['endDate'] ?? lastOfMonth();

$smarty->assign('startDate', $startDate);
$smarty->assign('endDate', $endDate);
