<?php
/**
 * @name Util.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181123
 */

namespace Inc\Claz;

use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;

class Util
{
    public static $timebreaks = array();

    /**
     * Verify page access via valid path. The PHP files that can be directly
     * accessed (index.php, login.php, logout.php, etc.) define this constant.
     * So all other php files should check this function to prevent a user from
     * trying to access that file directly.
     */
    public static function directAccessAllowed() {
        $allowDirectAccess = (isset($GLOBALS['allow_direct_access']) ? $GLOBALS['allow_direct_access'] : false);
        if (!$allowDirectAccess) {
            header("HTTP/1.0 404 Not Found");
            exit();
        }
    }

    /**
     * Set the global that allows php files to be access. Example: Call this method
     * in the index.php file. Then all other php file access while processing the
     * request process normally. If an attempt is made to access a php file other
     * than one that first calls this method, the process will terminate either
     * due to no autoloader defined or the Util::directAccessAllowed() method rejects
     * the request.;
     */
    public static function allowDirectAccess() {
        $GLOBALS['allow_direct_access'] = true;
    }

    /**
     * Create a drop down list for the specified array.
     * @param array $choiceArray Array of string values to stored in drop down list.
     * @param string $defVal Default value to selected option in list for.
     * @return String containing the HTML code for the drop down list.
     */
    public static function dropDown($choiceArray, $defVal) {
        $line = "<select name='value'>\n";
        foreach ($choiceArray as $key => $value) {
            $key_parm = Util::htmlsafe($key) . "' " . ($key == $defVal ? "selected style='font-weight: bold'" : "");
            $val_parm = Util::htmlsafe($value);
            $line .= "<option value='{$key_parm}'>{$val_parm}</option>\n";
        }
        $line .= "</select>\n";
        return $line;
    }

    /**
     * Replace all non-alphanumeric, dash, underscore and period characters with an underscore.
     * @param string $str String to be escaped.
     * @return string Escaped string.
     */
    public static function filenameEscape($str)
    {
        // Returns an escaped value.
        $safe_str = preg_replace('/[^a-z0-9\-_\.]/i','_',$str);
        return $safe_str;
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
    public static function getCustomPath($name, $mode = 'template') {
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
     * @param array $biller
     * @return string path to biller logo if present, else default SI logo.
     */
    public static function getLogo($biller) {
        $url = self::getURL();

        if (empty($biller['logo'])) {
            return $url . "templates/invoices/logos/_default_blank_logo.png";
        }
        return $url . "templates/invoices/logos/$biller[logo]";
    }

    /**
     * @return array List of logo files.
     */
    public static function getLogoList() {
        $dirname = "templates/invoices/logos";
        $ext = array("jpg", "png", "jpeg", "gif");
        $files = array();
        $handle = opendir($dirname);
        if ($handle !== false) {
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
     * @param array $smarty
     */
    public static function loginLogo($smarty) {
        $defaults = SystemDefaults::loadValues();
        // Not a post action so set up company logo and name to display on login screen.
        //<img src="extensions/user_security/images/{$defaults.company_logo}" alt="User Logo">
        $image = "templates/invoices/logos/" . $defaults['company_logo'];
        if (is_readable($image)) {
            $imgWidth = 0;
            $imgHeight = 0;
            $maxWidth = 100;
            $maxHeight = 100;
            list($width, $height, $type, $attr) = getimagesize($image);
            if ($type != $type || $attr != $attr) {
                // No action, test exists to eliminate unused variable warning.
                echo "modules.auth.login.php - This code is never executed.";
            }

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
            $comp_logo_lines =
                "<div style='display:inline-block;width:$w1;'>" .
                    "<img src='$image' alt='Company Logo' " .
                         ($imgHeight == 0 ? "" : "height='$imgHeight' ") .
                         ($imgWidth  == 0 ? "" : "width='$imgWidth' ") . "/>" .
                "</div>";
            $smarty->assign('comp_logo_lines', $comp_logo_lines);
            $txt_align = "left";
        } else {
            $w2 = "100%";
            $txt_align = "center";
        }
        $comp_name_lines =
            "<div style='display:inline-block;width:$w2;vertical-align:middle;'>" .
                "<h1 style='margin-left:20px;text-align:$txt_align;'>" .
                    $defaults['company_name_item'] .
                "</h1>" .
            "</div>";

        $smarty->assign('comp_name_lines', $comp_name_lines);
    }

    /**
     * @return string
     */
    public static function getURL() {
        global $api_request, $config;

        if ($api_request) {
            $_SERVER['FULL_URL'] = "";
            return "";
        }

        $dir = dirname($_SERVER['PHP_SELF']);
        // remove incorrect slashes for WinXP etc.
        $dir = str_replace('\\', '', $dir);

        // set the port of http(s) section
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $_SERVER['FULL_URL'] = "https://";
        } else {
            $_SERVER['FULL_URL'] = "http://";
        }

        $http_host = (empty($_SERVER['HTTP_HOST']) ? "" : $_SERVER['HTTP_HOST']);
        $_SERVER['FULL_URL'] .= $config->authentication->http . $http_host . $dir;

        if (strlen($_SERVER['FULL_URL']) > 1 &&
            substr($_SERVER['FULL_URL'], -1, 1) != '/') $_SERVER['FULL_URL'] .= '/';

        return $_SERVER['FULL_URL'];
    }

    /**
     * @param $str
     * @return string
     */
    public static function htmlsafe($str) {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param $str
     * @return bool|null|string|string[]
     */
    public static function urlsafe($str) {
        $str = preg_replace('/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/', '', $str);
        if (preg_match('/^\s*javascript/i', $str)) {
            return false;  // no javascript urls
        }
        $str = self::htmlsafe($str);
        return $str;
    }

    /**
     * @param $html
     * @return string Purified HTML
     */
    public static function outhtml($html) {
        try {
            $config = HTMLPurifier_Config::createDefault();

            // configuration goes here:
            $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
            $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

            $purifier = new HTMLPurifier($config);
            return $purifier->purify($html);
        } catch (Exception $e) {
            error_log("Util::outhtml() - Error: " . $e->getMessage());
        }
        return '';
    }

    /**
     * @param string $action 'set' to set the current time. 'report' to
     *          report the elapsed time.
     * @param string Descriptive label to the break.
     * @return string;
     */
    public static function timer($action, $label) {
        $result = '';
        $action = strtolower($action);
        if ($action == 'set') {
            $label = empty($label) ? 'Break ' . (count(Util::$timebreaks) + 1) : $label;
            Util::$timebreaks[] = array($label, microtime(true));
        } else if ($action == 'report') {
            $label = empty($label) ? 'End' : $label;
            Util::$timebreaks[] = array($label, microtime(true));
            $result = '';
            for ($i = 1; $i < count(Util::$timebreaks); $i++) {
                $cur = Util::$timebreaks[$i];
                $cur_label = $cur[0];
                $cur_time = $cur[1];
                $prev = Util::$timebreaks[$i-1];
                $prev_label = $prev[0];
                $prev_time = $prev[1];

                $diff = ($cur_time - $prev_time) * 60;

                if ($i == 1) {
                    $result = "\nTime initially set by $prev_label";
                }
                $result .= "\n";

                $result .= "$cur_label: $diff seconds";
            }
            if (empty($result)) {
                $result = 'No time interval set.';
            }
            Util::$timebreaks = array(); // Clear the reported info
        } else {
            $result = "Util::timer() - Invalid action[$action].";
        }

        return $result;
    }

}