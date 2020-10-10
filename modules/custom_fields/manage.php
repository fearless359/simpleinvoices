<?php

use Inc\Claz\CustomFields;
use Inc\Claz\Util;

/*
 * Script: manage.php
 *     Custom fields manage page
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *     https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cfs = CustomFields::getAll();
$smarty->assign("cfs",$cfs);
$smarty->assign('number_of_rows', count($cfs));

$smarty->assign('pageActive', 'custom_field');
$smarty->assign('activeTab', '#setting');
