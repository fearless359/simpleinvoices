<?php
namespace Inc\Claz;

/**
 * UserSecurity class
 * @author Rich
 */
class UserSecurity {

    /**
     * Build the pattern for the specified password constraints.
     * @return string Password pattern.
     */
    public static function buildPwdPattern() {
        global $smarty;
        $defaults = $smarty->tpl_vars['defaults']->value;

        //(?=^.{8,}$)(?=^[a-zA-Z])(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)(?![.\n]).*$
        $pwd_pattern = "(?=^.{" . $defaults['password_min_length'] . ",}$)(?=^[a-zA-Z])";

        if ($defaults['password_upper'] == 1) {
            $pwd_pattern .= "(?=.*[A-Z])";
        }

        if ($defaults['password_lower'] > 0) {
            $pwd_pattern .= "(?=.*[a-z])";
        }

        if ($defaults['password_number'] > 0) {
            $pwd_pattern .= "(?=.*\d)";
        }

        if ($defaults['password_special'] > 0) {
            $pwd_pattern .= "(?=.*\W)";
        }
        $pwd_pattern .= "(?![.\\n]).*$";

        return $pwd_pattern;
    }

}
