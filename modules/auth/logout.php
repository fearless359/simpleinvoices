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

Util::destroyOldAndStartNewSession();

header('Location: .');
