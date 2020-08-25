<?php

use Inc\Claz\CustomersPastDue;
use Inc\Claz\Util;

/*
 * Script: report_past_due.php collecting past due information.
 * Author: Richard Rowley
 */
global $menu, $smarty;

Util::directAccessAllowed();
$language = $smarty->tpl_vars['defaults']->language;
$smarty->assign('cust_info', CustomersPastDue::getCustInfo($language));

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu'      , $menu);
