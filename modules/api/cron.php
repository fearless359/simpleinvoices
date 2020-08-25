<?php
use Inc\Claz\Cron;
use Inc\Claz\Encode;
use Inc\Claz\PdoDbException;

/*
 * Typical Cron Job to run each day:
 * #SimpleInvoices recurrence - run each day at 1AM
 * 0 1 * * * /usr/bin/wget -q -O - http://localhost/api-cron >/dev/null 2>&1
 *
 * Typical expansion of mod rewrite using the .htaccess file
 * api-cron => index.php?module=api&amp;view=cron
 */
ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

// remove hard coding for multi-domain usage
// $cron->domain_id=1;
try {
    $message = Cron::run();
} catch (PdoDbException $pde) {
    exit("cron.php - Unable to do Cron::run(). Error: {$pde->getMessage()}");
} catch (Exception $exp) {
    exit("cron.php - Unable to do Cron::run() - 2. Error: {$exp->getMessage()}");
}
try {
    // json
    // header('Content-type: application/json');
    // echo encode::json( $message, 'pretty' );

    // xml
    ob_end_clean();
    header('Content-type: application/xml');
    echo Encode::xml($message);
} catch (Exception $exp) {
    echo $exp->getMessage();
}
error_log("$message");
