<?php
use Inc\Claz\Encode;
use Inc\Claz\Inventory;

ini_set('max_execution_time', 600); //600 seconds = 10 minutes

$message = Inventory::sendReorderNotificationEmail();

try {
    ob_end_clean();
    header('Content-type: application/xml');
    echo Encode::xml( $message );
} catch (Exception $e) {
    echo $e->getMessage();
}
