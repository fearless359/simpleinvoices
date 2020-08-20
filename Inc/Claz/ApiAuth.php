<?php

namespace Inc\Claz;

use Zend_Log;
use Zend_Session_Namespace;

/**
 * Class ApiAuth
 * @package Inc\Claz
 */
class ApiAuth
{
    public static function authenticate(?string $module, Zend_Session_Namespace $authSession): void
    {
Log::out("ApiAuth - in authenticate module[{$module}]", Zend_Log::DEBUG);

        // API calls don't use the auth module
        if ($module != 'api') {
            Log::out("ApiAuth::authenticate() - auth_session - " . print_r($authSession,true), Zend_Log::DEBUG);
            /** @noinspection PhpUndefinedFieldInspection */
            Log::out("ApiAuth::authenticate() - auth_session->id[{$authSession->id}]", Zend_Log::DEBUG);
            Log::out("ApiAuth::authenticate() - _GET - " . print_r($_GET,true), Zend_Log::DEBUG);
            // If we don't have an active session, force login screen.
            if (!isset($authSession->id)) {
                // Make sure we have a module entry in the $_GET array.
                if (!isset($_GET['module'])) {
                    $_GET['module'] = '';
                }

                // If this is not an "auth" module request, then force login screen.
                if ($_GET['module'] !== "auth" && $_GET['module'] !== "install") {
                    Log::out("ApiAuth::authenticate() - Forcing direct to login screen.", Zend_Log::DEBUG);
                    header('Location: index.php?module=auth&view=login');
                    exit();
                }
            }
        }
    }
}
