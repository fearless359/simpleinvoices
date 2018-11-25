<?php

use Inc\Claz\CustomFields;
use Inc\Claz\DomainId;
use Inc\Claz\Extensions;
use Inc\Claz\Util;

/*
 *  Script: add.php
 *      Billers add page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-07-29
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

Util::directAccessAllowed();

$files = Util::getLogoList();
$smarty->assign("files", $files);

$domain_id = DomainId::get();
$smarty->assign("domain_id", $domain_id);

// Only load labels if they are defined. Screen will only
// show what is loaded.
$customFieldLabel = CustomFields::getLabels(true);

if (!empty($_POST['name'])) {
    include ("modules/billers/save.php");
}

$smarty->assign('files', $files);
$smarty->assign('customFieldLabel', $customFieldLabel);

$smarty->assign('pageActive', 'biller');
$smarty->assign('subPageActive', 'biller_add');
$smarty->assign('active_tab', '#people');
