<?php

namespace Inc\Claz;

use Exception;
use Zend_Log;
use Zend_Session_Namespace;

Class CheckPermission
{
    /**
     * @param string $module
     * @param \Zend_Acl $acl
     */
    public static function isAllowed($module, $acl): void
    {
        global $LANG;
        $checkPermission = "";
        try {
            $auth_session = new Zend_Session_Namespace('Zend_Auth');
            $acl_view = (isset($_GET['view']) ? $_GET['view'] : null);
            $acl_action = (isset($_GET['action']) ? $_GET['action'] : null);
            if (empty($acl_action)) {
                // no action is given
                if (!empty($acl_view)) {
                    // view is available with no action
                    if ($acl->isAllowed($auth_session->role_name, $module, $acl_view)) {
                        $checkPermission = "allowed";
                    } else {
                        $checkPermission = "denied";
                    }
                }
            } else {
                // action available
                $checkPermission = $acl->isAllowed($auth_session->role_name,
                    $module, $acl_action) ? "allowed" : "denied"; // allowed
            }

            // basic customer page check
            if ($auth_session->role_name == 'customer' && $module == 'customers' &&
                $_GET['id'] != $auth_session->user_id) {
                $checkPermission = "denied";
            }

            // customer invoice page add/edit check since no acl for invoices
            // @formatter:off
            if (($auth_session->role_name == 'customer') && ($module == 'invoices')) {
                if ($acl_view == 'itemised'   ||
                    $acl_view == 'total'      ||
                    $acl_view == 'consulting' ||
                    $acl_action == 'view'     ||
                   ($acl_action != '' && isset($_GET['id']) && $_GET['id'] != $auth_session->user_id)) {
                    $checkPermission = "denied";
                }
            }
            // @formatter:on
        } catch (Exception $zse) {
            $checkPermission = "denied";
        }

        Log::out("CheckPermission::isAllowed - checkPermission[{$checkPermission}]", Zend_Log::DEBUG);
        if ($checkPermission == "denied") {
            exit($LANG['denied_page']);
        }
    }
}