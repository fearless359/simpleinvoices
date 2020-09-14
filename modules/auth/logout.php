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

session_name('SiAuth');
session_start();
session_destroy();
header('Location: .');
