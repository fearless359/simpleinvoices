<?php

use Inc\Claz\Util;

/*
 * Script: login.php
 * 	Login page
 *
 * License:
 *	 GPL v3 or above
 */
Util::allowDirectAccess();

$menu = true;

try {
    \Zend_Session::start();
} catch (\Zend_Session_Exception $zse) {
    error_log("modules.auth.logout.php - Error: " . $zse->getMessage());
    die("modules.auth.logout.php - Unable to start a session");
}

\Zend_Session::destroy(true);
header('Location: .');
