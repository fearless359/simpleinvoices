<?php

/**
 * UserSecurity class
 * @author Rich
 *
 */
class UserSecurity {

    /**
     * Static function to add the session_timeout column to the system_defaults
     * table if it is not present.
     */
    public static function loadCompanyName($patchCount) {
        global $pdoDb, $LANG;

        $rows = null;
        try {
            $pdoDb->setSelectList("value");
            $pdoDb->addSimpleWhere("name", "company_name_item");
            $rows = $pdoDb->request("SELECT", "system_defaults");
        } catch (PdoDbException $pde) {
            // no action needed
        }
        if (empty($rows)) {
            $LANG['company_name_item'] = 'SimpleInvoices';
            if ($patchCount > 297) {
                error_log("UserSecurity - loadCompanyName(): Failed to retrieve company name item.");
            }
        } else {
            $LANG['company_name_item'] = $rows[0]['value'];
        }

        $rows = null;
        try {
            $pdoDb->setSelectList("value");
            $pdoDb->addSimpleWhere("name", "company_name");
            $rows = $pdoDb->request("SELECT", "system_defaults");
        } catch(PdoDbException $pde) {
            // No action needed
        }
        if (empty($rows)) {
            $LANG['company_name'] = 'SimpleInvoices';
            if ($patchCount > 297) {
                error_log("UserSecurity - loadCompanyName(): Failed to retrieve company name.");
            }
        } else {
            $LANG['company_name'] = $rows[0]['value'];
        }
    }

    /**
     * Build the pattern for the specified password constrants.
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
