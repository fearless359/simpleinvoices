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

session_name(SESSION_NAME);
session_start();
//session_unset();
session_destroy();
header('Location: .');
