<?php

use Inc\Claz\SqlPatchManager;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Allow thrown error to be passed up to the user.
$smarty -> assign("patches", SqlPatchManager::sqlPatches());

$smarty -> assign('pageActive', 'sqlpatch');
$smarty -> assign('active_tab', '#setting');
