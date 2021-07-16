<?php
global $reverseIncludeAllCustomersDefault, $smarty;

if (isset($reverseIncludeAllCustomersDefault)) {
    $includeAllCustomersDefault = $reverseIncludeAllCustomersDefault ? "yes" : "no";
} else {
    $includeAllCustomersDefault = "no";
}

$includeAllCustomers = $_POST['includeAllCustomers'] ?? $includeAllCustomersDefault;

$smarty->assign('includeAllCustomers', $includeAllCustomers);
