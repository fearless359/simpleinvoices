<?php
use Inc\Claz\Cron;

global $smarty;

try {
    $smarty->assign('message', Cron::run());
} catch (Exception $exp) {
    exit("modules/cron/run.php Unexpected error: {$exp->getMessage()}");
}
