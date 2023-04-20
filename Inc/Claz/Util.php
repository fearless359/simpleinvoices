<?php
namespace Inc\Claz;

use DateTime;
use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use IntlDateFormatter;
use NumberFormatter;
use Smarty;

/**
 * Class Util
 * @package Inc\Claz
 */
class Util
{
    private const DATE_FORMAT_PARAMETER = "/(full|long|short|medium|month|monthShort)/";

    public static array $timeBreaks = [];

    /**
     * Verify page access via valid path. The PHP files that can be directly
     * accessed (index.php, login.php, logout.php, etc.) define this constant.
     * So all other php files should check this function to prevent a user from
     * trying to access that file directly.
     */
    public static function directAccessAllowed(): void
    {
        $allowDirectAccess = $GLOBALS['allow_direct_access'] ?? false;
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
     * due to no autoloader defined or the self::directAccessAllowed() method rejects
     * the request.;
     */
    public static function allowDirectAccess(): void
    {
        $GLOBALS['allow_direct_access'] = true;
    }

    /**
     * Create a drop-down list for the specified array.
     * @param array $choiceArray Array of string values to stored in drop down list.
     * @param string $defVal Default value to selected option in list for.
     * @return String containing the HTML code for the drop-down list.
     */
    public static function dropDown(array $choiceArray, string $defVal): string
    {
        $line = "<select name='value'>\n";
        foreach ($choiceArray as $key => $value) {
            $keyParm = self::htmlSafe($key) . "' " . ($key == $defVal ? "selected style='font-weight: bold'" : "");
            $valParm = self::htmlSafe($value);
            $line .= "<option value='$keyParm'>$valParm</option>\n";
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
        /** @noinspection RegExpRedundantEscape */
        $pattern = '/[^a-z0-9\-_\.\/]/i';
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
        Log::out("Util::getCustomPath() - name[$name] mode[$mode]");
        if ($mode == 'template') {
            if (file_exists("{$myCustomPath}defaultTemplate/$name.tpl")) {
                $out = "{$myCustomPath}defaultTemplate/$name.tpl";
            } elseif (file_exists("templates/default/$name.tpl")) {
                $out = "templates/default/$name.tpl";
            }
        }
        if ($mode == 'module') {
            if (file_exists("{$myCustomPath}modules/$name.php")) {
                $out = "{$myCustomPath}modules/$name.php";
            } elseif (file_exists("modules/$name.php")) {
                $out = "modules/$name.php";
            }
        }
        return $out;
    }

    /**
     * @param array $biller
     * @return string relative path to biller logo if present, else default SI logo.
     */
    public static function getLogo(array $biller): string
    {
        if (empty($biller['logo'])) {
            return "templates/invoices/logos/_default_blank_logo.png";
        }
        return "templates/invoices/logos/$biller[logo]";
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
        $logoImage = "templates/invoices/logos/" . $defaults['company_logo'];
        $smarty->assign('logoImage', $logoImage);
        $logoName = $defaults['company_name_item'];
        $smarty->assign('logoCompanyName', $logoName);

        $imgWidth = 0;
        $imgHeight = 0;
        if (is_readable($logoImage)) {
            $maxWidth = 100;
            $maxHeight = 100;
            /** @noinspection PhpUnusedLocalVariableInspection */
            [$width, $height, $type, $attr] = getimagesize($logoImage);

            if ($width > $maxWidth || $height > $maxHeight) {
                $wp = $maxWidth / $width;
                $hp = $maxHeight / $height;
                $percent = min($wp, $hp);
                $imgWidth = $width * $percent;
                $imgHeight = $height * $percent;
            }
        }

        $imgWidth = $imgWidth == 0 ? 100 : $imgWidth;
        $imgHeight = $imgHeight == 0 ? 100 : $imgHeight;

        $smarty->assign('logoImageWidth', $imgWidth . "%");
        $smarty->assign('logoImageHeight', $imgHeight . "%");
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
        $_SERVER['FULL_URL'] .= $config['authenticationHttp'] . $httpHost . $dir;

        if (strlen($_SERVER['FULL_URL']) > 1 && !str_ends_with($_SERVER['FULL_URL'], '/')) {
            $_SERVER['FULL_URL'] .= '/';
        }

        return $_SERVER['FULL_URL'];
    }

    /**
     * Format numbers.
     * Note: This is a wrapper for the <b>NumberFormatter</b> function.
     * @param float|int|string|null $number Number to be formatted
     * @param int|null $precision Decimal precision.
     * @param string $locale Locale the number is to be formatted for.
     * @return string Formatted number.
     * @noinspection DuplicatedCode
     */
    public static function number(float|int|string|null $number, ?int $precision = null, string $locale = ""): string
    {
        global $config;

        if (!isset($number)) {
            $number = 0;
        }

        if (empty($locale)) {
            $locale = $config['localLocale'];
        }

        if (!isset($precision)) {
            $precision = $config['localPrecision'];
        }

        $numberFormatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $numberFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
        $formattedNumber = $numberFormatter->format($number);

        return empty($formattedNumber) ? '0' : $formattedNumber;
    }

    /**
     * Format number in default form.
     * Note: Default form is without leading & trailing zeros, and locale decimal point (period or comma).
     * @param float|int|string|null $number Numeric value to be formatted.
     * @param int|null $precision Decimal places for the number. Optional, precision from $config file used if not specified.
     * @param string $locale Locale to use for formatting the number. Optional, locale from $config file used if not specified.
     * @return string Formatted string.
     * @noinspection DuplicatedCode
     */
    public static function numberTrim(float|int|string|null $number, ?int $precision = null, string $locale = ""): string
    {
        global $config;

        if (!isset($number)) {
            $number = 0;
        }

        if (empty($locale)) {
            $locale = $config['localLocale'];
        }

        if (!isset($precision)) {
            $precision = $config['localPrecision'];
        }

        $numberFormatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
        $formattedNumber = $numberFormatter->format($number);

        // Calculate the decimal point right offset.
        $position = ($precision + 1) * -1;

        // Get character in the decimal point position. Check if it is a
        // decimal point. If so, remove it if it is followed only by zeros.
        // Note this differs in that it won't trim trailing zeroes if there
        // are non-zero characters following the decimal point. (ex: 1.10 won't trim).
        $chr = substr($formattedNumber, $position, 1);
        if ($chr == '.' || $chr == ',') {
            $formattedNumber = rtrim(trim($formattedNumber, '0'), '.,');
        }

        return empty($formattedNumber) ? '0' : $formattedNumber;
    }

    /**
     * Format number in default currency form.
     * @param float|int|string|null $number Numeric value to be formatted.
     * @param string $locale Locale to use for formatting the number.
     *          Optional, locale from $config file used if not specified.
     * @param string $currencyCode
     * @return string Formatted string.
     */
    public static function currency(float|int|string|null $number, string $locale = "", string $currencyCode = ""): string
    {
        global $config;

        if (!isset($number)) {
            $number = 0;
        }

        if (empty($currencyCode)) {
            $currencyCode = $config['localCurrencyCode'];
        }

        if (empty($locale)) {
            $locale = $config['localLocale'];
        }

       $precision = $config['localPrecision'];

        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
        return $numberFormatter->formatCurrency($number, $currencyCode);
    }

    /**
     * Format a date value.
     * @param string $dateVal Date value to be formatted.
     * @param string $dateFormat (Optional) Date format. Values are:
     *        <ul>
     *          <li><b>full</b>      : IntlDateFormatter::FULL        - Ex: Friday, May 8, 2020</li>
     *          <li><b>long</b>      : IntlDateFormatter::LONG        - Ex: May 8, 2020</li>
     *          <li><b>medium</b>    : IntlDateFormatter::MEDIUM      - Ex: 05/08/2020</li>
     *          <li><b>month</b>     : IntlDateFormatter::MONTH       - Ex: 05</li>
     *          <li><b>monthShort</b>: IntlDateFormatter::MONTH_SHORT - Ex: 5</li>
     *          <li><b>short</b>     : IntlDateFormatter::SHORT       - Ex: 5/6/2017</li>
     *        </ul>
     *        Defaults to <b>medium</b>.
     * @return string <b>$date</b> formatted per option settings.
     */
    public static function date(string $dateVal, string $dateFormat = "medium"): string
    {
        if (!preg_match(self::DATE_FORMAT_PARAMETER, $dateFormat)) {
            $str = "Util::date() - Invalid date format, $dateFormat, specified.";
            error_log($str);
            exit($str);
        }

        // Break date apart from time. Date will be index 0
        $parts = explode(' ', $dateVal);
        $dateTime = DateTime::createFromFormat('Y-m-d', $parts[0]);

        $pattern = match ($dateFormat) {
            "full" => "l, F d, Y",
            "long" => "F d, Y",
            "short" => "m/d/y",
            "month" => "F",
            "month_short" => "M",
            default => "m/d/Y"
        };

        return $dateTime->format($pattern);
    }

    /**
     * Generate a printable date format in the requested form.
     * @param string $dateTimeVal Must be "Y-m-d H:i:s" format value as obtained from the database.
     * @param string $locale typically the locale field for the invoice preference.
     * @param string $format translates to IntlDateFormatter constants: short, medium, long, full, none.
     *                  Defaults to none.
     * @return string Formatted date for user display.
     */
    public static function intlDate(string $dateTimeVal, string $locale, string $format = "none"): string
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeVal);

        $format = strtolower($format);
        $intlFormat = match($format) {
            "short" => IntlDateFormatter::SHORT,
            "medium" => IntlDateFormatter::MEDIUM,
            "long" => IntlDateFormatter::LONG,
            "full" => IntlDateFormatter::FULL,
            default => IntlDateFormatter::NONE
        };
        $formatter = new IntlDateFormatter($locale, $intlFormat, IntlDateFormatter::NONE);
        return $formatter->format($dateTime);
    }

    /**
     * Convert a localized number back to the format stored in the database.
     * @param string $number
     * @return string Number formatted for database storage (ex: 12.345,67 converts to 12345.67)
     */
    public static function dbStd(string $number): string
    {
        global $config;

        $locale = $config['localLocale'];
        $currencyCode = $config['localCurrencyCode'];
        $precision = $config['localPrecision'];

        $typeCurrency = false;
        $parts = str_split($number);
        foreach($parts as $item) {
            if (preg_match('/[^\-,.0-9]/', $item)) {
                $typeCurrency = true;
                break;
            }
        }

        $formatterType = $typeCurrency ? NumberFormatter::CURRENCY :NumberFormatter::DECIMAL;

        $numberFormatter = new NumberFormatter($locale, $formatterType);
        $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);

        if ($typeCurrency) {
            $formattedNumber = $numberFormatter->parseCurrency($number, $currencyCode);
        } else {
            $formattedNumber = $numberFormatter->parse($number);
        }

        return empty($formattedNumber) ? '0' : $formattedNumber;
    }

    /**
     * Return a list of locale values that SI has language files for.
     * @return array of locales
     */
    public static function getLocaleList(): array
    {
        $dirs = [];
        $list = array_diff(scandir("lang"), ['.', '..']);
        foreach ($list as $item) {
            if (preg_match('/^[a-z][a-z]_[A-Z][A-Z]$/', $item)) {
                $dirs[] = $item;
            }
        }
        return $dirs;
    }

    /**
     * This will take any dash or underscore turn it into a space, run ucwords against
     * it, so it capitalizes the first letter in all words separated by a space then it
     * turns and deletes all spaces.
     * @param string $str String to convert
     * @param array $dontStrip If specified, an array of non-alphanumeric characters not to strip.
     * @return string
     */
    public static function camelCase(string $str, array $dontStrip = []): string
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-z0-9'.implode('',$dontStrip).']+/', ' ',$str))));
    }

    /**
     * Create an array containing a range of elements.
     * Used a smarty template modifier as capability for php range as modifier was deprecated in PHP 8.1
     * @param string|int|float $start
     * @param string|int|float $end
     * @param int|float $step
     * @return array
     */
    public static function utilRange(string|int|float $start, string|int|float $end, int|float $step=1): array
    {
        return range($start, $end, $step);
    }

    /**
     * Make sure $str is properly encoded for html display.
     * @param int|string $str String to make safe.
     * @return string Safe string for html display.
     */
    public static function htmlSafe(int|string $str): string
    {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }

    public static function urlEncode(?string $str): string
    {
        if (empty($str)) {
            return '';
        }
        return urlencode($str);
    }

    /**
     * Make sure URL is safe for html use.
     * @param string|array $str
     * @return bool|string
     */
    public static function urlSafe(string|array $str): bool|string
    {
        /** @noinspection RegExpRedundantEscape */
        /** @noinspection RegExpDuplicateCharacterInClass */
        $pattern = '/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/';
        $str = preg_replace($pattern, '', $str);
        $pattern = '/^\s*javascript/i';
        if (preg_match($pattern, $str)) {
            return false;  // no javascript urls
        }
        return self::htmlSafe($str);
    }

    /**
     * @param string|null $html
     * @return string Purified HTML
     */
    public static function outHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        try {
            $config = HTMLPurifier_Config::createDefault();

            // configuration goes here:
            $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
            $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

            $purifier = new HTMLPurifier($config);
            return $purifier->purify($html);
        } catch (Exception $exp) {
            error_log("self::outHtml() - Error: " . $exp->getMessage());
        }
        return '';
    }

    public static function destroyOldAndStartNewSession(): void
    {
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');

        session_name(SESSION_NAME);
        session_start();
        session_regenerate_id(true);

        Log::out("Util::destroyOldAndStartNewSession() - New session_id[" . session_id() . "]");
    }

    /**
     * Number of minutes before session times out.
     * @param int $timeoutMinutes
     * @param string $module
     * @param string $view
     */
    public static function sessionTimeout(int $timeoutMinutes, string &$module, string &$view): void
    {
        $timeoutSeconds = $timeoutMinutes * 60;
        $now = time();
        if (isset($_SESSION['timeout']) && $now > $_SESSION['timeout']) {
            self::destroyOldAndStartNewSession();
            $module = 'auth';
            $view = 'login';
        }

        $_SESSION['timeout'] = $now + $timeoutSeconds;
    }

    /**
     * @param string $action 'set' to set the current time. 'report' to
     *          report the elapsed time.
     * @param string $label Descriptive label to the break.
     * @return string;
     */
    public static function timer(string $action, string $label): string
    {
        $result = '';
        $action = strtolower($action);
        if ($action == 'set') {
            $label = empty($label) ? 'Break ' . count(self::$timeBreaks) + 1 : $label;
            self::$timeBreaks[] = [$label, microtime(true)];
        } elseif ($action == 'report') {
            $label = empty($label) ? 'End' : $label;
            self::$timeBreaks[] = [$label, microtime(true)];
            for ($ndx = 1; $ndx < count(self::$timeBreaks); $ndx++) {
                $cur = self::$timeBreaks[$ndx];
                $curLabel = $cur[0];
                $curTime = $cur[1];
                $prev = self::$timeBreaks[$ndx - 1];
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
            self::$timeBreaks = []; // Clear the reported info
        } else {
            $result = "self::timer() - Invalid action[$action].";
        }

        return $result;
    }

    /**
     * Ensure that there is a time value in the datetime object.
     *
     * @param string $in_date Datetime string in the format, "YYYY-MM-DD HH:MM:SS".
     *        Note: If time part is "00:00:00" it will be set to the current time.
     * @return string Datetime string with time set.
     */
    public static function sqlDateWithTime(string $in_date): string
    {
        $parts = explode(' ', $in_date);
        $date = $parts[0] ?? "";
        $time = $parts[1] ?? "00:00:00";
        if (!$time || $time == '00:00:00') {
            $time = date('H:i:s');
        }

        return "$date $time";
    }

    public static function trimmer(?string $string): ?string
    {
        if (!isset($string)) {
            return null;
        }

        return trim($string);
    }

    /**
     * Truncate a given string
     *
     * @param string|null $string - the string to truncate
     * @param int $max - the max length in characters to truncate the string to
     * @param string $rep - characters to be added at end of truncated string
     * @return string truncated to specified length.
     */
    public static function truncateStr(?string $string, int $max = 20, string $rep = ''): string
    {
        if (empty($string)) {
            return "";
        }
        
        if (strlen($string) <= $max + strlen($rep)) {
            return $string;
        }
        $leave = $max - strlen($rep);
        return substr_replace($string, $rep, $leave);
    }


    public static function holidayLogo(string $logo): string
    {
        // @formatter:off
        $holidays = [
            "_newyears."      => "1",
            "_valentines."    => "2",
            "_easter."        => "4",
            "_independence."  => "7",
            "_thanksgiving."  => "11",
            "_christmas."     => "12",
            "_shevat."        => "2",
            "_purim."         => "3",
            "_passover."      => "4",
            "_shavuot."       => "5",
            "_rosh_hashanah." => "9",
            "_yom_kippur."    => "10",
            "_chanukah."      => "12"
        ];
        // @formatter:on

        $parts = explode('.', $logo);
        if (count($parts) == 2) {
            $now = new DateTime();
            $currMonth = $now->format('m');
            foreach ($holidays as $holiday => $month) {
                if ($currMonth == $month) {
                    $tmpLogo = $parts[0] . $holiday . $parts[1];
                    if (file_exists($tmpLogo)) {
                        $logo = $tmpLogo;
                        break;
                    }
                }
            }
        }

        return $logo;
    }
}
