<?php

namespace Inc\Claz;

use Exception;

/**
 * Class CheckPermission
 * @package Inc\Claz
 */
class CheckPermission
{
    /**
     * Check to see if this user has permission to access this resource.
     * Note that the "administrator" and "all" roles have permission to access all resources.
     * @param string $resource
     * @param string $permission
     * @return bool
     */
    public static function isAllowed(string $resource, string $permission): bool
    {
        $role = SiAcl::getSessionRole();
        if ($role == "administrator" || $role == "all") {
            return true;
        }

        try {
            $checkPermission = SiAcl::isAllowed($resource, $permission) ? 'allowed' : 'denied';
        } catch (Exception $zse) {
            error_log("CheckPermission::isAllowed() - resource[$resource] permission[$permission] role[$role] error: {$zse->getMessage()}");
            $checkPermission = "denied";
        }

        if ($checkPermission == "denied") {
            return false;
        }
        return true;
    }
}
