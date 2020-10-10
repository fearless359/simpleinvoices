<?php
global $reverseIncludeAllCustomersDefault, $smarty;

if (isset($reverseIncludeAllCustomersDefault)) {
    $includeAllCustomersDefault = $reverseIncludeAllCustomersDefault ? "yes" : "no";
} else {
    $includeAllCustomersDefault = "no";
}

$includeAllCustomers = isset($_POST['includeAllCustomers']) ? $_POST['includeAllCustomers'] : $includeAllCustomersDefault;

$smarty->assign('includeAllCustomers', $includeAllCustomers);
