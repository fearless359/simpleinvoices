<?php
/*
* Script: login.php
* 	Login page
*
* License:
*	 GPL v3 or above
*/
$menu = true;

// we must never forget to start the session
//so config.php works ok without using index.php define browse
if (!defined("BROWSE")) define("BROWSE", "browse");

try {
    Zend_Session::start();
} catch (Zend_Session_Exception $zse) {
    error_log("modules.auth.logout.php - Error: " . $zse->getMessage());
    die("modules.auth.logout.php - Unable to start a session");
}

Zend_Session::destroy(true);
header('Location: .');
