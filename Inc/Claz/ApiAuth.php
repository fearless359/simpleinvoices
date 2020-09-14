<?php

namespace Inc\Claz;

/**
 * Class ApiAuth
 * @package Inc\Claz
 */
class ApiAuth
{
    public static function authenticate(?string $module): void
    {

        // API calls don't use the auth module
        if ($module != 'api') {
            session_name('SiAuth');
            session_start();
            Log::out("ApiAuth::authenticate() - id[{$_SESSION['id']}]");
            Log::out("ApiAuth::authenticate() - _GET - " . print_r($_GET,true));
            // If we don't have an active session, force login screen.
            if (!isset($_SESSION['id'])) {
                // Make sure we have a module entry in the $_GET array.
                if (!isset($_GET['module'])) {
                    $_GET['module'] = '';
                }

                // If this is not an "auth" module request, then force login screen.
                if ($_GET['module'] !== "auth" && $_GET['module'] !== "install") {
                    Log::out("ApiAuth::authenticate() - Forcing direct to login screen.");
                    header('Location: index.php?module=auth&view=login');
                    exit();
                }
            }
        }
    }
}
