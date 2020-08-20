<?php

namespace Inc\Claz;

use Exception;
use Zend_Acl;
use Zend_Log;
use Zend_Session_Namespace;

/**
 * Class CheckPermission
 * @package Inc\Claz
 */
class CheckPermission
{

    public static function isAllowed(?string $module, Zend_Acl $acl): void
    {
        global $LANG;
        $checkPermission = "";
        try {
            $authSession = new Zend_Session_Namespace('Zend_Auth');
            $aclView = isset($_GET['view']) ? $_GET['view'] : null;
            $aclAction = isset($_GET['action']) ? $_GET['action'] : null;
            if (empty($aclAction)) {
                // no action is given
                if (!empty($aclView)) {
                    // view is available with no action
                    /** @noinspection PhpUndefinedFieldInspection */
                    if ($acl->isAllowed($authSession->role_name, $module, $aclView)) {
                        $checkPermission = "allowed";
                    } else {
                        $checkPermission = "denied";
                    }
                }
            } else {
                // action available
                /** @noinspection PhpUndefinedFieldInspection */
                $checkPermission = $acl->isAllowed($authSession->role_name,
                    $module, $aclAction) ? "allowed" : "denied"; // allowed
            }

            // basic customer page check
            /** @noinspection PhpUndefinedFieldInspection */
            if ($authSession->role_name == 'customer' && $module == 'customers' &&
                $_GET['id'] != $authSession->user_id) {
                $checkPermission = "denied";
            }

            // customer invoice page add/edit check since no acl for invoices
            // @formatter:off
            /** @noinspection PhpUndefinedFieldInspection */
            if ($authSession->role_name == 'customer' && $module == 'invoices') {
                /** @noinspection PhpUndefinedFieldInspection */
                if ($aclView == 'itemised'   ||
                    $aclView == 'total'      ||
                    $aclView == 'consulting' ||
                    $aclAction == 'view'     ||
                   $aclAction != '' && isset($_GET['id']) && $_GET['id'] != $authSession->user_id) {
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