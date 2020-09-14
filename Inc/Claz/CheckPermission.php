<?php

namespace Inc\Claz;

use Exception;

/**
 * Class CheckPermission
 * @package Inc\Claz
 */
class CheckPermission
{
    public static function isAllowed(string $resource, string $permission): bool
    {
        try {
            $checkPermission = SiAcl::isAllowed($resource, $permission) ? 'allowed' : 'denied';
        } catch (Exception $zse) {
            $role = SiAcl::getSessionRole();
            error_log("CheckPermission::isAllowed() - resource[$resource] permission[$permission] role[$role] error: {$zse->getMessage()}");
            $checkPermission = "denied";
        }

        if ($checkPermission == "denied") {
            return false;
        }
        return true;
    }
}
