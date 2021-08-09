<?php

namespace Inc\Claz;

/**
 * UserSecurity class
 * @author Rich
 */
class UserSecurity
{

    /**
     * Build the pattern for the specified password constraints specified
     * in the system_defaults table.
     * @return string Password pattern.
     */
    public static function buildPwdPattern(): string
    {
        global $smarty;
        $defaults = $smarty->tpl_vars['defaults']->value;

        //(?=^.{8,}$)(?=^[a-zA-Z])(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)(?![.\n]).*$
        $pwdPattern = "(?=^.{" . $defaults['password_min_length'] . ",}$)(?=^[a-zA-Z])";

        if ($defaults['password_upper'] == 1) {
            $pwdPattern .= "(?=.*[A-Z])";
        }

        if ($defaults['password_lower'] > 0) {
            $pwdPattern .= "(?=.*[a-z])";
        }

        if ($defaults['password_number'] > 0) {
            $pwdPattern .= "(?=.*\d)";
        }

        if ($defaults['password_special'] > 0) {
            $pwdPattern .= "(?=.*\W)";
        }
        $pwdPattern .= "(?![.\\n]).*$";

        return $pwdPattern;
    }

}
