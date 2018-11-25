<?php
/*
 *  Script: functions.php
 *      Contain all non db query functions used in SimpleInvoices
 *
 *  Authors:
 *      Justin Kelly
 *
 *  License:
 *      GNU GPL2 or above
 *
 *  Date last edited:
 *      2018-11-23 by Richard Rowley
 */

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
 * @return array
 */
// By RCR 20181123
//function getLangList() {
//    $startdir = './lang/';
//    $ignoredDirectory = array();
//    $ignoredDirectory[] = '.';
//    $ignoredDirectory[] = '..';
//    $ignoredDirectory[] = '.svn';
//    $folderList = array();
//    if (is_dir($startdir)) {
//        if ($dh = opendir($startdir)) {
//            while (($folder = readdir($dh)) !== false) {
//                if (!(array_search($folder, $ignoredDirectory) > -1)) {
//                    if (filetype($startdir . $folder) == "dir") {
//                        $folderList[] = $folder;
//                    }
//                }
//            }
//            closedir($dh);
//        }
//    }
//    sort($folderList);
//    return ($folderList);
//}

/**
 * @param $sth
 * @param $count
 * @return string
 */
// By RCR 20181123
//function sql2xml($sth, $count) {
//    // you can choose any name for the starting tag
//    $xml = ("<result>");
//    $xml .= "<page>1</page>";
//    $xml .= "<total>" . $count . "</total>";
//    foreach ($sth as $row) {
//        $xml .= ("<tablerow>");
//        foreach ($row as $key => $value) {
//            $xml .= ("<$key>" . htmlsafe($value) . "</$key>");
//        }
//        $xml .= ("</tablerow>");
//    }
//    $xml .= ("</result>");
//
//    return $xml;
//}

/**
 * Generates a token to be used on forms that change something
 * @param bool $action
 * @param bool $userid
 * @param bool $tickTock
 * @return string
 */
// By RCR 20181123
//function siNonce($action = false, $userid = false, $tickTock = false) {
//    global $config;
//    global $auth_session;
//
//    $tickTock = ($tickTock) ? $tickTock : floor(time() / $config->nonce->timelimit);
//
//    if (!$userid) {
//        $userid = $auth_session->id;
//    }
//
//    $hash = md5($tickTock . ':' . $config->nonce->key . ':' . $userid . ':' . $action);
//
//    return $hash;
//}

/**
 * Verify nonce token
 * @param $hash
 * @param $action
 * @param bool $userid
 * @return bool
 */
// By RCR 20181123
//function verifySiNonce($hash, $action, $userid = false) {
//    global $config;
//
//    $tickTock = floor(time() / $config->nonce->timelimit);
//    if (!empty($hash) &&
//        ($hash === siNonce($action, $userid) || $hash === siNonce($action, $userid, $tickTock - 1))) {
//        return true;
//    }
//
//    return false;
//}

/**
 * Put this before an action is committed make sure to put a unique $action
 * @param string $action
 * @param bool $userid
 */
// By RCR 20181123
//function requireCSRFProtection($action = 'all', $userid = false) {
//    verifySiNonce($_REQUEST['csrfprotectionbysr'], $action, $userid) or die('CSRF Attack Detected');
//}

/**
 * @param string $action
 * @param bool $userid
 * @return string
 */
// By RCR 20181123
//function antiCSRFHiddenInput($action = 'all', $userid = false) {
//    return '<input type="hidden" name="csrfprotectionbysr" value="' . htmlsafe(siNonce($action, $userid)) . '" />';
//}

