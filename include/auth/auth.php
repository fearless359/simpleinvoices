<?php
use Inc\Claz\Log;

global $auth_session, $module;
/*
 * API calls don't use the auth module
 */
if ($module != 'api') {
    Log::out("auth.php - auth_session - " . print_r($auth_session,true), \Zend_Log::DEBUG);
    Log::out("auth_session->id[{$auth_session->id}]", \Zend_Log::DEBUG);
    Log::out("_GET - " . print_r($_GET,true), \Zend_Log::DEBUG);
    if (!isset($auth_session->id)) {
        if (!isset($_GET['module'])) {
            $_GET['module'] = '';
        }
        if ($_GET['module'] !== "auth") {
            header('Location: index.php?module=auth&view=login');
            exit();
        }
    }
}
