<?php

// You can either copy the original code and add you modify it, here.
global $smarty;

// Or you can require the overridden module (to resists to future updates)
include '../modules/index/index.php';

// and just add some code
$myContent	="<h3>My Own Tag is Here</H3>";

// We add it to the template
$smarty->assign("myTag", $myContent);
