<?php

use Inc\Claz\CustomFields;
use Inc\Claz\DomainId;
use Inc\Claz\Util;

/*
 *  Script: add.php
 *      Billers add page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2018-12-11 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

Util::directAccessAllowed();

if (!empty($_POST['op']) && $_POST['op'] == 'create') {
    include "modules/billers/save.php";
} else {
    $files = Util::getLogoList();
    $smarty->assign("files", $files);

    $smarty->assign("domain_id", DomainId::get());

    // Only load labels if they are defined. Screen will only show what is loaded.
    $customFieldLabel = CustomFields::getLabels(true);

    $smarty->assign('files', $files);
    $smarty->assign('customFieldLabel', $customFieldLabel);

    $smarty->assign('pageActive', 'biller');
    $smarty->assign('subPageActive', 'billerCreate');
    $smarty->assign('activeTab', '#people');
}
