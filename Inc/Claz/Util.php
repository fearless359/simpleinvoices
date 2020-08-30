<?php
namespace Inc\Claz;

use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use Smarty;

/**
 * Class Util
 * @package Inc\Claz
 */
class Util
{
    public static array $timebreaks = [];

    /**
     * Verify page access via valid path. The PHP files that can be directly
     * accessed (index.php, login.php, logout.php, etc.) define this constant.
     * So all other php files should check this function to prevent a user from
     * trying to access that file directly.
     */
    public static function directAccessAllowed(): void
    {
        $allowDirectAccess = isset($GLOBALS['allow_direct_access']) ? $GLOBALS['allow_direct_access'] : false;
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
    public static function allowDirectAccess(): void
    {
        $GLOBALS['allow_direct_access'] = true;
    }

    /**
     * Create a drop down list for the specified array.
     * @param array $choiceArray Array of string values to stored in drop down list.
     * @param string $defVal Default value to selected option in list for.
     * @return String containing the HTML code for the drop down list.
     */
    public static function dropDown(array $choiceArray, string $defVal): string
    {
        $line = "<select name='value'>\n";
        foreach ($choiceArray as $key => $value) {
            $keyParm = Util::htmlsafe($key) . "' " . ($key == $defVal ? "selected style='font-weight: bold'" : "");
            $valParm = Util::htmlsafe($value);
            $line .= "<option value='{$keyParm}'>{$valParm}</option>\n";
        }
        $line .= "</select>\n";
        return $line;
    }

    /**
     * Replace all non-alphanumeric, dash, underscore and period characters with an underscore.
     * @param string $str String to be escaped.
     * @return string Escaped string.
     */
    public static function filenameEscape(string $str): string
    {
        // Returns an escaped value.
        $pattern = '/[^a-z0-9\-_\.]/i';
        return preg_replace($pattern, '_', $str);
    }

    /**
     * Build path for the specified file type if it exists.
     * The first attempt is to make a custom path, if that file doesn't
     * exist, the regular path is checked. The first path that is for an
     * existing file is the path returned.
     * @param string $name Name or dir/name of file without an extension.
     * @param string $mode Set to "template" or "module".
     * @return string|null File path or NULL if no file path determined.
     */
    public static function getCustomPath(string $name, string $mode = 'template'): ?string
    {
        $myCustomPath = "custom/";
        $out = null;
        if ($mode == 'template') {
            if (file_exists("{$myCustomPath}default_template/{$name}.tpl")) {
                $out = "{$myCustomPath}default_template/{$name}.tpl";
            } elseif (file_exists("templates/default/{$name}.tpl")) {
                $out = "templates/default/{$name}.tpl";
            }
        }
        if ($mode == 'module') {
            if (file_exists("{$myCustomPath}modules/{$name}.php")) {
                $out = "{$myCustomPath}modules/{$name}.php";
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
    public static function getLogo(array $biller): string
    {
        $url = self::getURL();

        if (empty($biller['logo'])) {
            return $url . "templates/invoices/logos/_default_blank_logo.png";
        }
        return $url . "templates/invoices/logos/$biller[logo]";
    }

    /**
     * @return array List of logo files.
     */
    public static function getLogoList(): array
    {
        $dirname = "templates/invoices/logos";
        $ext = ["jpg", "png", "jpeg", "gif"];
        $files = [];
        $handle = opendir($dirname);
        if ($handle !== false) {
            while (false !== ($file = readdir($handle))) {
                for ($ndx = 0; $ndx < sizeof($ext); $ndx++) {
                    // NOT case sensitive: OK with JpeG, JPG, ecc.
                    if (stristr($file, "." . $ext[$ndx])) {
                        $files[] = $file;
                    }
                }
            }
            closedir($handle);
        }

        sort($files);
        return $files;
    }

    public static function loginLogo(Smarty $smarty): void
    {
        $defaults = SystemDefaults::loadValues();
        // Not a post action so set up company logo and name to display on login screen.
        //<img src="extensions/user_security/images/{$defaults.company_logo}" alt="User Logo">
        $image = "templates/invoices/logos/" . $defaults['company_logo'];
        if (is_readable($image)) {
            $imgWidth = 0;
            $imgHeight = 0;
            $maxWidth = 100;
            $maxHeight = 100;
            /** @noinspection PhpUnusedLocalVariableInspection */
            list($width, $height, $type, $attr) = getimagesize($image);

            if ($width > $maxWidth || $height > $maxHeight) {
                $wp = $maxWidth / $width;
                $hp = $maxHeight / $height;
                $percent = $wp > $hp ? $hp : $wp;
                $imgWidth = $width * $percent;
                $imgHeight = $height * $percent;
            }
            if ($imgWidth > 0 && $imgWidth > $imgHeight) {
                $w1 = "20%";
                $w2 = "78%";
            } else {
                $w1 = "18%";
                $w2 = "80%";
            }
            $compLogoLines =
                "<div style='display:inline-block;width:$w1;'>" .
                "<img src='$image' alt='Company Logo' " .
                ($imgHeight == 0 ? "" : "height='$imgHeight' ") .
                ($imgWidth == 0 ? "" : "width='$imgWidth' ") . "/>" .
                "</div>";
            $smarty->assign('comp_logo_lines', $compLogoLines);
            $txtAlign = "left";
        } else {
            $w2 = "100%";
            $txtAlign = "center";
        }
        $compNameLines =
            "<div style='display:inline-block;width:$w2;vertical-align:middle;'>" .
            "<h1 style='margin-left:20px;text-align:$txtAlign;'>" .
            $defaults['company_name_item'] .
            "</h1>" .
            "</div>";

        $smarty->assign('comp_name_lines', $compNameLines);
    }

    public static function getURL(): string
    {
        global $apiRequest, $config;

        if ($apiRequest) {
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

        $httpHost = empty($_SERVER['HTTP_HOST']) ? "" : $_SERVER['HTTP_HOST'];
        $_SERVER['FULL_URL'] .= $config->authentication->http . $httpHost . $dir;

        if (strlen($_SERVER['FULL_URL']) > 1 && substr($_SERVER['FULL_URL'], -1, 1) != '/') {
            $_SERVER['FULL_URL'] .= '/';
        }

        return $_SERVER['FULL_URL'];
    }

    /**
     * Make sure $str is properly encoded for html display.
     * @param string|int $str String to make safe.
     * @return string Safe string for html display.
     */
    public static function htmlsafe($str): string
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Make sure URL is safe for html use.
     * @param string|array $str
     * @return bool|null|string|string[]
     */
    public static function urlsafe($str)
    {
        $pattern = '/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/';
        $str = preg_replace($pattern, '', $str);
        $pattern = '/^\s*javascript/i';
        if (preg_match($pattern, $str)) {
            return false;  // no javascript urls
        }
        $str = self::htmlsafe($str);
        return $str;
    }

    /**
     * @param string $html
     * @return string Purified HTML
     */
    public static function outhtml(string $html): string
    {
        try {
            $config = HTMLPurifier_Config::createDefault();

            // configuration goes here:
            $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
            $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

            $purifier = new HTMLPurifier($config);
            return $purifier->purify($html);
        } catch (Exception $exp) {
            error_log("Util::outhtml() - Error: " . $exp->getMessage());
        }
        return '';
    }

    /**
     * @param string $action 'set' to set the current time. 'report' to
     *          report the elapsed time.
     * @param string Descriptive label to the break.
     * @return string;
     */
    public static function timer(string $action, string $label): string
    {
        $result = '';
        $action = strtolower($action);
        if ($action == 'set') {
            $label = empty($label) ? 'Break ' . (count(Util::$timebreaks) + 1) : $label;
            Util::$timebreaks[] = [$label, microtime(true)];
        } elseif ($action == 'report') {
            $label = empty($label) ? 'End' : $label;
            Util::$timebreaks[] = [$label, microtime(true)];
            $result = '';
            for ($ndx = 1; $ndx < count(Util::$timebreaks); $ndx++) {
                $cur = Util::$timebreaks[$ndx];
                $curLabel = $cur[0];
                $curTime = $cur[1];
                $prev = Util::$timebreaks[$ndx - 1];
                $prevLabel = $prev[0];
                $prevTime = $prev[1];

                $diff = ($curTime - $prevTime) * 60;

                if ($ndx == 1) {
                    $result = "\nTime initially set by $prevLabel";
                }
                $result .= "\n";

                $result .= "$curLabel: $diff seconds";
            }
            if (empty($result)) {
                $result = 'No time interval set.';
            }
            Util::$timebreaks = []; // Clear the reported info
        } else {
            $result = "Util::timer() - Invalid action[$action].";
        }

        return $result;
    }

}