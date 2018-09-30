<?php
/*
 *  Script: login.php
 *      Login page
 *
 *  Last modified:
 *		2018-09-24 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 */
if (!function_exists('loginLogo')) {
    function loginLogo($smarty, $defaults) {
        // Not a post action so set up company logo and name to display on login screen.
        //<img src="extensions/user_security/images/{$defaults.company_logo}" alt="User Logo">
        $image = "templates/invoices/logos/" . $defaults['company_logo'];
        if (is_readable($image)) {
            $imgWidth = 0;
            $imgHeight = 0;
            $maxWidth = 100;
            $maxHeight = 100;
            $type = "";
            $attr = "";
            list($width, $height, $type, $attr) = getimagesize($image);

            if (($width > $maxWidth || $height > $maxHeight)) {
                $wp = $maxWidth / $width;
                $hp = $maxHeight / $height;
                $percent = ($wp > $hp ? $hp : $wp);
                $imgWidth = ($width * $percent);
                $imgHeight = ($height * $percent);
            }
            if ($imgWidth > 0 && $imgWidth > $imgHeight) {
                $w1 = "20%";
                $w2 = "78%";
            } else {
                $w1 = "18%";
                $w2 = "80%";
            }
            $comp_logo_lines = "<div style='display:inline-block;width:$w1;'>" .
                               "  <img src='$image' alt='Company Logo' " .
                                  ($imgHeight == 0 ? "" : "height='$imgHeight' ") .
                                  ($imgWidth  == 0 ? "" : "width='$imgWidth' ") . "/>" .
                               "</div>";
            $smarty->assign('comp_logo_lines', $comp_logo_lines);
            $txt_align = "left";
        } else {
            $w2 = "100%";
            $txt_align = "center";
        }
        $comp_name_lines = "<div style='display:inline-block;width:$w2;vertical-align:middle;'>" .
                           "  <h1 style='margin-left:20px;text-align:$txt_align;'>" .
                                  $defaults['company_name_item'] . "</h1>" .
                           "</div>";

        $smarty->assign('comp_name_lines', $comp_name_lines);
    }
}

global $patchCount,
       $defaults,
       $smarty,
       $pdoDb;

$menu = false;

if (!defined("BROWSE")) define("BROWSE", "browse");

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");

Zend_Session::start();
$errorMessage = '';
loginLogo($smarty, $defaults);

if (empty($_POST['user']) || empty($_POST['pass'])) {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
} else {
    $username = $_POST['user'];
    $password  = $_POST['pass'];
    if (User::verifyPassword($username, $password)) {
        Zend_Session::start();

        $timeout = SystemDefaults::getDefaultSessionTimeout();
        if ($timeout <= 0) {
            $timeout = 60;
        }

        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $authNamespace->setExpirationSeconds($timeout * 60);

        $jn = new Join('LEFT', 'user_role', 'r');
        $jn->addSimpleItem('u.role_id', new DbField('r.id'));
        $pdoDb->addToJoins($jn);

        $pdoDb->setSelectList(array('u.id', 'u.username', 'u.email', new DbField('r.name', 'role_name'), 'u.domain_id', 'u.user_id'));
        $pdoDb->addSimpleWhere('u.username', $username, 'AND');
        $pdoDb->addSimpleWhere('u.enabled', ENABLED);
        $rows = $pdoDb->request('SELECT', 'user', 'u');
        foreach ($rows[0] as $key => $value) {
            $authNamespace->$key = $value;
        }

        if (isset($authNamespace->role_name) && $authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
            header('Location: index.php?module=customers&view=details&action=view&id='.$authNamespace->user_id);
        } else {
            header('Location: .');
        }
    } else {
        $errorMessage = STD_LOGIN_FAILED_MSG;
	}
}
// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage",$errorMessage);
