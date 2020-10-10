<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

/*
 *  Script: manage.php
 *      Custom flags manage page
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2018-12-13
 *
 *  License:
 *      GPL v3 or above
 */
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cflgs = CustomFlags::getAll();
$smarty->assign('cflgs', $cflgs);
$smarty->assign('number_of_rows', count($cflgs));

$smarty->assign('pageActive', 'custom_flags');
$smarty->assign('activeTab', '#setting');
