<?php

/*
 * Script: functions.php
 * Contain all non db query functions used in SimpleInvoices
 *
 * Authors:
 * - Justin Kelly
 *
 * License:
 * GNU GPL2 or above
 *
 * Date last edited:
 * Tue Jan 19 12:55:00 PST 2016
 * Mon Oct 28 12:00:00 IST 2013
 */

function checkLogin() {
    if (!defined("BROWSE")) {
        echo "You Cannot Access This Script Directly, Have a Nice Day.";
        exit();
    }
}

/**
 * Build path for the specified file type if it exists.
 * The first attempt is to make a custom path, if that file doesn't
 * exist, the regular path is checked. The first path that is for an
 * existing file is the path returned.
 * @param string $name Name or dir/name of file without an extension.
 * @param string $mode Set to "template" or "module".
 * @return mixed File path or NULL if no file path determined.
 */
function getCustomPath($name, $mode = 'template') {
    $my_custom_path = "custom/";
    $out = NULL;
    if ($mode == 'template') {
        if (file_exists("{$my_custom_path}default_template/{$name}.tpl")) {
            $out = "{$my_custom_path}default_template/{$name}.tpl";
        } elseif (file_exists("templates/default/{$name}.tpl")) {
            $out = "templates/default/{$name}.tpl";
        }
    }
    if ($mode == 'module') {
        if (file_exists("{$my_custom_path}modules/{$name}.php")) {
            $out = "{$my_custom_path}modules/{$name}.php";
        } elseif (file_exists("modules/{$name}.php")) {
            $out = "modules/{$name}.php";
        }
    }
    return $out;
}

/**
 * Global function to see if an extension is enabled.
 * @param string $ext_name Name of the extension to check for.
 * @return true if enabled, false if not.
 */
function isExtensionEnabled($ext_name) {
    global $ext_names;
    $enabled = false;
    foreach ($ext_names as $name) {
        if ($name == $ext_name) {
            $enabled = true;
            break;
        }
    }
    return $enabled;
}

/**
 * @return array List of logo file.
 */
function getLogoList() {
    $dirname = "templates/invoices/logos";
    $ext = array("jpg", "png", "jpeg", "gif");
    $files = array();
    if ($handle = opendir($dirname)) {
        while (false !== ($file = readdir($handle))) {
            for ($i = 0; $i < sizeof($ext); $i++) {
                // NOT case sensitive: OK with JpeG, JPG, ecc.
                if (stristr($file, "." . $ext[$i])) $files[] = $file;
            }
        }
        closedir($handle);
    }

    sort($files);
    return $files;
}

/**
 * @param array $biller
 * @return string path to biller logo if present, else default SI logo.
 */
function getLogo($biller) {
    $url = getURL();

    if (empty($biller['logo'])) {
        return $url . "templates/invoices/logos/_default_blank_logo.png";
    }
    return $url . "templates/invoices/logos/$biller[logo]";
}

/**
 * Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie,
 * biller/product/customer)
 *
 * @param string $field - The custom field in question
 * @return mixed $custom field name or false if undefined entry in $field.
 */
function get_custom_field_name($field) {
    global $LANG;

    // grab the first character of the field variable
    $get_cf_letter = $field[0];
    // grab the last character of the field variable
    $get_cf_number = $field[strlen($field) - 1];

    // function to return false if invalid custom_field
    switch ($get_cf_letter) {
        case "b":
            $custom_field_name = $LANG['biller'];
            break;
        case "c":
            $custom_field_name = $LANG['customer'];
            break;
        case "i":
            $custom_field_name = $LANG['invoice'];
            break;
        case "p":
            $custom_field_name = $LANG['products'];
            break;
        default:
            $custom_field_name = false;
    }

    // Append the rest of the string
    $custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number;
    return $custom_field_name;
}

/**
 * Create a drop down list for the specified array.
 * @param array $choiceArray Array of string values to stored in drop down list.
 * @param string $defVal Default value to selected option in list for.
 * @return String containing the HTML code for the drop down list.
 */
function dropDown($choiceArray, $defVal) {
    $dropDown = '<select name="value">' . "\n";
    foreach ($choiceArray as $key => $value) {
        // @formatter:off
        $dropDown .= '<option ' .
                          'value="' . htmlsafe($key) . '" ' .
                          ($key == $defVal ? 'selected style="font-weight: bold" >' :
                                             '>') .
                          htmlsafe($value) .
                     '</option>' .
                     "\n";
        // @formatter:on
    }
    $dropDown .= "</select>\n";
    return $dropDown;
}

/**
 * @return array
 */
function getLangList() {
    $startdir = './lang/';
    $ignoredDirectory = array();
    $ignoredDirectory[] = '.';
    $ignoredDirectory[] = '..';
    $ignoredDirectory[] = '.svn';
    $folderList = array();
    if (is_dir($startdir)) {
        if ($dh = opendir($startdir)) {
            while (($folder = readdir($dh)) !== false) {
                if (!(array_search($folder, $ignoredDirectory) > -1)) {
                    if (filetype($startdir . $folder) == "dir") {
                        $folderList[] = $folder;
                    }
                }
            }
            closedir($dh);
        }
    }
    sort($folderList);
    return ($folderList);
}

/**
 * @param $sth
 * @param $count
 * @return string
 */
function sql2xml($sth, $count) {
    // you can choose any name for the starting tag
    $xml = ("<result>");
    $xml .= "<page>1</page>";
    $xml .= "<total>" . $count . "</total>";
    foreach ($sth as $row) {
        $xml .= ("<tablerow>");
        foreach ($row as $key => $value) {
            $xml .= ("<$key>" . htmlsafe($value) . "</$key>");
        }
        $xml .= ("</tablerow>");
    }
    $xml .= ("</result>");

    return $xml;
}

/**
 * Truncate a given string
 *
 * @param $string - the string to truncate
 * @param $max - the max length in characters to truncate the string to
 * @param $rep - characters to be added at end of truncated string
 * @return string truncated to specified length.
 */
function si_truncate($string, $max = 20, $rep = '') {
    if (strlen($string) <= ($max + strlen($rep))) {
        return $string;
    }
    $leave = $max - strlen($rep);
    return substr_replace($string, $rep, $leave);
}

/**
 * @param $str
 * @return string
 */
function htmlsafe($str) {
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

/**
 * @param $str
 * @return bool|null|string|string[]
 */
function urlsafe($str) {
    $str = preg_replace('/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/', '', $str);
    if (preg_match('/^\s*javascript/i', $str)) {
        return false;  // no javascript urls
    }
    $str = htmlsafe($str);
    return $str;
}

/**
 * @param $html
 * @return string Purified HTML
 * @throws HTMLPurifier_Exception
 */
function outhtml($html) {
    $config = HTMLPurifier_Config::createDefault();

    // configuration goes here:
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

/**
 * Generates a token to be used on forms that change something
 * @param bool $action
 * @param bool $userid
 * @param bool $tickTock
 * @return string
 */

function siNonce($action = false, $userid = false, $tickTock = false) {
    global $config;
    global $auth_session;

    $tickTock = ($tickTock) ? $tickTock : floor(time() / $config->nonce->timelimit);

    if (!$userid) {
        $userid = $auth_session->id;
    }

    $hash = md5($tickTock . ':' . $config->nonce->key . ':' . $userid . ':' . $action);

    return $hash;
}

/**
 * Verify nonce token
 * @param $hash
 * @param $action
 * @param bool $userid
 * @return bool
 */
function verifySiNonce($hash, $action, $userid = false) {
    global $config;

    $tickTock = floor(time() / $config->nonce->timelimit);
    if (!empty($hash) &&
        ($hash === siNonce($action, $userid) || $hash === siNonce($action, $userid, $tickTock - 1))) {
        return true;
    }

    return false;
}

/**
 * Put this before an action is committed make sure to put a unique $action
 * @param string $action
 * @param bool $userid
 */
function requireCSRFProtection($action = 'all', $userid = false) {
    verifySiNonce($_REQUEST['csrfprotectionbysr'], $action, $userid) or die('CSRF Attack Detected');
}

/**
 * @param string $action
 * @param bool $userid
 * @return string
 */
function antiCSRFHiddenInput($action = 'all', $userid = false) {
    return '<input type="hidden" name="csrfprotectionbysr" value="' . htmlsafe(siNonce($action, $userid)) . '" />';
}

/**
 * Mask a string with specified string of characters exposed.
 * @param string $value Value to be masked.
 * @param string $chr Character to replace masked characters.
 * @param int $num_to_show Number of characters to leave exposed.
 * @return string Masked value.
 */
function maskValue($value, $chr='x', $num_to_show=4) {
    $len = strlen($value);
    if ($len <= $num_to_show) return $value;
    $mask_len = $len - $num_to_show;
    $masked_value = "";
    for ($i=0; $i<$mask_len; $i++) $masked_value .= $chr;
    $masked_value .= substr($value, $mask_len);
    return $masked_value;
}
