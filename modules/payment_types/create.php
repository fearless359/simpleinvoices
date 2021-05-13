<?php

use Inc\Claz\DomainId;
use Inc\Claz\Util;

global $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed.
Util::directAccessAllowed();

if (!empty($_POST['pt_description'])) {
    include 'modules/payment_types/save.php';
} else {
    $smarty->assign('domain_id', DomainId::get());

    $smarty->assign('pageActive', 'pymtTypes');
    $smarty->assign('subPageActive', 'pymtTypesCreate');
    $smarty->assign('activeTab', '#settings');
}
