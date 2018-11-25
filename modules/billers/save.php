<?php

use Inc\Claz\Biller;
use Inc\Claz\Util;

/*
 *  Script: save.php
 *      Biller save page
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-01-16 by Rich Rowley to add signature field
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$op = (empty($_POST['op']) ? "" : $_POST['op']);

$saved = false;
if ( $op === 'insert_biller') {
    if (Biller::insertBiller() > 0) $saved = true;
} else if ($op === 'edit_biller') {
    if (isset($_POST['save_biller'])) {
        if (Biller::updateBiller()) $saved = true;
    }
}

$smarty->assign('saved',$saved);

$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
