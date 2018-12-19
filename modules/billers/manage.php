<?php

use Inc\Claz\Biller;
use Inc\Claz\Util;

/*
 * Script: manage.php
 * Biller manage page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2016-01-16 by Rich Rowley to add signature field
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group */
global $LANG, $smarty;

// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
Util::isAccessAllowed();

$billers = array();
$rows = Biller::getAll();
foreach ($rows as $row) {
    $row['vname'] = $LANG['view'] . ' ' . Util::htmlsafe($row['name']);
    $row['ename'] = $LANG['edit'] . ' ' . Util::htmlsafe($row['name']);
    $row['image'] = ($row['enabled'] == ENABLED ? 'images/common/tick.png' : 'images/common/cross.png');
    $billers[] = $row;
}

$smarty->assign('billers', $billers);
$smarty->assign('number_of_rows', count($billers));

$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
