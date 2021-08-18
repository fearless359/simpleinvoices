<?php
global $reverseFilterByDateRangeDefault, $smarty;

if (isset($reverseFilterByDateRangeDefault)) {
    $filterByDateRangeDefault = $reverseFilterByDateRangeDefault ? "no" : "yes";
} else {
    $filterByDateRangeDefault = "yes";
}

$filterByDateRange = $_POST['filterByDateRange'] ?? $filterByDateRangeDefault;

$smarty->assign('filterByDateRange', $filterByDateRange);
