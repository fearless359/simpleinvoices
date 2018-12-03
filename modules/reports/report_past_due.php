<?php
/*
 * Script: report_past_due.php collecting past due information.
 * Author: Richard Rowley
 */
global $config, $menu, $smarty;

checkLogin();

$cust_info = CustomersPastDue::getCustInfo($config->local->locale);
$smarty->assign('cust_info', $cust_info);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu'      , $menu);
