<?php
use Inc\Claz\Cron;

global $smarty;

$message = Cron::run();
$smarty->assign('message', $message);
