<?php

namespace Inc\Claz;

/**
 * Class DomainId
 * @package Inc\Claz
 */
class DomainId
{

    /**
     * Get domain ID to use for this user.
     * @param int|null $domainId
     * @return int
     */
    public static function get(?int $domainId = null): int
    {
        if (PHP_SESSION_ACTIVE !== session_status()){
            session_name('SiAuth');
            session_start();
        }
        // default when session value absent - fake auth, whether auth needed or not
        $domId = 1;

        // empty is used rather than isset so that 0 value is not used.
        if (!empty($domainId)) {
            // if domain_id is set in the code then use this one
            $domId = $domainId;
        } elseif (!empty($_SESSION['domain_id'])) {
            // no preset value available
            // take session value since available
            // whether fakeAuth or not
            $domId = $_SESSION['domain_id'];
        }

        return $domId;
    }
}
