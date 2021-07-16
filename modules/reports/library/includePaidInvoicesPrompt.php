<?php
global $smarty;

$includePaidInvoices = $_POST['includePaidInvoices'] ?? "no";

$smarty->assign('includePaidInvoices', $includePaidInvoices);
