<?php

use Inc\Claz\DomainId;
use Inc\Claz\DynamicJs;
use Inc\Claz\Util;

global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::isAccessAllowed();

if (!empty($_POST['op']) && $_POST['op'] == 'add') {
    include 'modules/payment_types/save.php';
} else {
    DynamicJs::begin();
    DynamicJs::formValidationBegin("frmpost");
    DynamicJs::validateRequired("pt_description",$LANG['payment_type_description']);
    DynamicJs::formValidationEnd();
    DynamicJs::end();

    $smarty->assign('domain_id', DomainId::get());

    $smarty->assign('pageActive', 'payment_type');
    $smarty->assign('subPageActive', 'payment_types_add');
    $smarty->assign('active_tab', '#setting');
}