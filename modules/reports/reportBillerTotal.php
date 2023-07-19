<?php

global $LANG, $menu, $smarty;

use Inc\Claz\Biller;

include 'library/dateRangePrompt.php';
$smarty->assign('showAllReports', $_GET['showAllReports']);

if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'biller') {
    $billerId = intval($_SESSION['user_id']);
} else {
    $billerId = $_POST['billerId'] ?? 0;
}
$smarty->assign('billerId', $billerId);

if (empty($billerId)) {
    $billers = Biller::getAll();
} else {
    $billers = [];
    $billers[] = Biller::getOne($billerId);
}
$smarty->assign('billers', $billers);

$smarty->assign('title', "{$LANG['billerUc']} {$LANG['salesUc']} {$LANG['totalUc']}");

include "modules/reports/reportBillerTotalData.php";

$smarty->assign('pageActive', 'report');
$smarty->assign('activeTab', '#home');

if (!isset($menu)) {
    $menu = true; // Causes menu section of report gen page to display.
}
$smarty->assign('menu', $menu);
