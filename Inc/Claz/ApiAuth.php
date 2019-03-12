<?php

namespace Inc\Claz;

class ApiAuth
{
    /**
     * @param $module
     * @param \Zend_Session_Namespace $auth_session
     */
    public static function authenticate($module, $auth_session) {
        // API calls don't use the auth module
        if ($module != 'api') {
            Log::out("ApiAuth::authenticate() - auth_session - " . print_r($auth_session,true), \Zend_Log::DEBUG);
            Log::out("ApiAuth::authenticate() - auth_session->id[{$auth_session->id}]", \Zend_Log::DEBUG);
            Log::out("ApiAuth::authenticate() - _GET - " . print_r($_GET,true), \Zend_Log::DEBUG);
            // If we don't have an active session, force login screen.
            if (!isset($auth_session->id)) {
                // Make sure we have a module entry in the $_GET array.
                if (!isset($_GET['module'])) {
                    $_GET['module'] = '';
                }

                // If this is not an "auth" module request, then force login screen.
                if ($_GET['module'] !== "auth") {
                    Log::out("ApiAuth::authenticate() - Forcing direct to login screen.", \Zend_Log::DEBUG);
                    header('Location: index.php?module=auth&view=login');
                    exit();
                }
            }
        }
    }
}
