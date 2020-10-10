<?php
global $smarty;

$includePaidInvoices = empty($_POST['includePaidInvoices']) ? "no" : $_POST['includePaidInvoices'];

$smarty->assign('includePaidInvoices', $includePaidInvoices);
