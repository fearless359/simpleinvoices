<?php

namespace Inc\Claz;

use Exception;

/**
 * Class Config
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181009
 * @package Inc\Claz
 */
class Config
{
    public const CONFIG_FILE = "config/config.ini";
    public const CUSTOM_CONFIG_FILE = "config/custom.config.ini";

    private static array $customConfig;
    private static string $versionName = "";
    private static string $versionUpdateDate = "";

    /**
     * Make sure we have a custom.config.ini file that is consistent with the config.ini file.
     * @param string $section Typically the constant CONFIG_SECTION is passed.
     * @param bool $updateCustomConfig
     * @param string|null $configFile
     * @return array
     * @throws Exception
     */
    public static function init(string $section, bool $updateCustomConfig, ?string $configFile = null): array
    {
        // This parameter is used in the phpunit test.
        if (!isset($configFile)) {
            $configFile = self::CUSTOM_CONFIG_FILE;
        }

        if ($updateCustomConfig) {
            self::makeCustomConfig();
            self::updateConfig();
        }

        $config = parse_ini_file("./$configFile", true);
        if ($config === false) {
            SiError::out('generic', 'Config::init()', "Unable to parse ini file: $configFile");
        }

        self::$customConfig = $config[$section];

        if (isset(self::$customConfig['localLocale'])) {
            self::$customConfig['localLocaleGlobal'] = preg_replace('/^(.*)_(.*)$/', '$1-$2', self::$customConfig['localLocale']);
        } else {
            error_Log("config localLocaleGlobal not set");
        }

        return self::$customConfig;
    }

    /**
     * Make sure we have a custom.config.ini file. Copy config.ini is needed.
     */
    private static function makeCustomConfig(): void
    {
        // Create custom.config.ini file if it doesn't already exist
        if (!file_exists("./" . self::CUSTOM_CONFIG_FILE)) {
            copy("./" . self::CONFIG_FILE, "./" . self::CUSTOM_CONFIG_FILE);
        }
    }

    /**
     * Parse config files and generate the change list.
     * @throws Exception If invalid config file format.
     */
    private static function updateConfig(): void
    {
        $fp = fopen('./' . self::CONFIG_FILE, 'r');
        if ($fp === false) {
            SiError::out('generic', 'Config::update_config()', "Unable to open " . self::CONFIG_FILE);
        }

        $configInfo = self::loadFileInfo(self::CONFIG_FILE, $fp);
        fclose($fp);

        $fp = fopen('./' . self::CUSTOM_CONFIG_FILE, 'r');
        if ($fp === false) {
            SiError::out('generic', 'Config::update_config()', 'Unable to open ' . self::CUSTOM_CONFIG_FILE);
        }

        $customConfigInfo = self::loadFileInfo(self::CUSTOM_CONFIG_FILE, $fp);
        fclose($fp);

        self::updateCustomConfig(self::genChanges($configInfo, $customConfigInfo));
    }

    /**
     * Load the key/value pair information from a configuration file into an array.
     * @param string $filename Name of config file for exception message.
     * @param resource $fp load the file data into an associative array
     * @return array of key/value pair for sections within the configuration file.
     * @throws Exception if config file not formatted as expected.
     */
    private static function loadFileInfo(string $filename, $fp): array
    {
        $info = [];
        $section = null;
        while (($line = fgets($fp)) !== false) {
            switch (ConfigLines::lineType($line)) {
                case 'section':
                    /** @noinspection RegExpRedundantEscape */
                    $pattern = '/^\[(.*)\]/';
                    $section = trim(preg_replace($pattern, '$1', $line));
                    break;

                case 'pair':
                    $parts = explode('=', $line, 2);
                    $key = trim($parts[0]);
                    if (count($parts) == 2) {
                        $value = trim($parts[1]);
                    } else {
                        $value = '';
                    }

                    if (empty($section)) {
                        throw new Exception("Config::loadFileInfo() - Config file ($filename) not formatted correctly. " .
                            "Key/value pair found before section defined:\n" . $line);
                    }

                    $info[$section . '|' . $key] = new ConfigLines($section, $key, $value);
                    break;

                case 'other':
                default:
                    break;
            }
        }
        return $info;
    }

    /**
     * Compare configuration file items and create list of items to be added or
     * removed from the custom configuration file.
     * @param array $configInfo standard config file that has all items for SI.
     * @param array $customConfigInfo user custom configuration items list.
     * @return array configuration items to be added or removed from custom config.
     *      The returned array contains two associative entries, "new" and "old".
     *      The "new" entries need to be added to the custom config file. The "old"
     *      entries need to be marked as not in standard config file.
     */
    private static function genChanges(array $configInfo, array $customConfigInfo): array
    {
        $newItems = [];
        $oldItems = [];

        // The key is:  section|key_part_of_value_pair
        // The value is: ConfigLines object
        foreach ($configInfo as $key => $value) {
            $parts = explode('|', $key);
            if (!isset($customConfigInfo[$key])) {
                $newItems[$parts[0]][$parts[1]] = $value;
            }

            if ($parts[0] == 'production') {
                if ($parts[1] == 'versionName') {
                    self::$versionName = $value->getValue();
                } elseif ($parts[1] == 'versionUpdateDate') {
                    self::$versionUpdateDate = $value->getValue();
                }
            }
        }

        foreach ($customConfigInfo as $key => $value) {
            if (!isset($configInfo[$key])) {
                $parts = explode('|', $key);
                $oldItems[$parts[0]][$parts[1]] = $value;
            }
        }


        return [
            'new' => $newItems,
            'old' => $oldItems
        ];
    }

    /**
     * @param array $changes of items keyed by "new" or "old". The
     *      value of the new/old array is itself an array keyed by
     *      "section|key_part_of_key_value_pair".
     * @throws Exception if irrecoverable error found.
     */
    private static function updateCustomConfig(array $changes): void
    {
        $unmatched = $changes['old'];
        $newPairs = $changes['new'];
        $changed = false;

        $filenameNew = './' . self::CUSTOM_CONFIG_FILE . ".new";
        $fpNew = fopen($filenameNew, 'w');
        if ($fpNew === false) {
            exit("Config::updateCustomConfig() - Unable to open new './" . self::CUSTOM_CONFIG_FILE . ".new' file");
        }

        $fpCur = fopen('./' . self::CUSTOM_CONFIG_FILE, 'r');
        if ($fpCur === false) {
            exit("Config::updateCustomConfig() - Unable to open './" . self::CUSTOM_CONFIG_FILE . "'");
        }
        $section = null;
        $unmatchedFlagged = false;
        while (($line = fgets($fpCur)) !== false) {
            if (!$unmatchedFlagged) {
                $unmatchedFlagged = preg_match('/Possibly Deprecated/', $line);
            }

            switch (ConfigLines::lineType($line)) {
                case 'section':
                    fwrite($fpNew, $line);
                    /** @noinspection RegExpRedundantEscape */
                    $pattern = '/^[\t ]*\[(.*)\].*$/';
                    $section = trim(preg_replace($pattern, '$1', $line));
                    // Write out all new lines for this section
                    if (isset($newPairs[$section])) {
                        $changed = true;
                        foreach ($newPairs[$section] as $newpair) {
                            settype($newpair, 'object');
                            $newline = $newpair->getKey() . ' = ' . $newpair->getValue() . "\n";
                            fwrite($fpNew, $newline);
                        }
                    }
                    break;

                case 'pair':
                    $pattern = '/^[\t ]*([a-zA-Z0-9._]+)[\t ]*=.*$/';
                    $key = trim(preg_replace($pattern, '$1', $line));
                    if (!isset($section)) {
                        fclose($fpNew);
                        fclose($fpCur);
                        throw new Exception("Config::updateCustomConfig() - Key/pair ($line) found prior to section being set.");
                    }

                    if ($key == 'versionName') {
                        $replPattern = '${1}' . self::$versionName;
                        $newline = preg_replace('/^(.*= ).*$/', $replPattern, $line);
                        if (!$changed) {
                            $changed = $newline != $line;
                        }
                        fwrite($fpNew, $newline);
                    } elseif ($key == 'versionUpdateDate') {
                        $replPattern = '${1}' . self::$versionUpdateDate;
                        $newline = preg_replace('/^(.*= *).*$/', $replPattern, $line);
                        if (!$changed) {
                            $changed = $newline != $line;
                        }
                        fwrite($fpNew, $newline);
                    } elseif (isset($unmatched[$section][$key])) {
                        if (!$unmatchedFlagged) {
                            $changed = true;
                            fwrite($fpNew, ";******** Possibly Deprecated - not in config.ini ********\n");
                        }
                        fwrite($fpNew, $line);
                        if (!$unmatchedFlagged) {
                            fwrite($fpNew, ";**************** End Possibly Deprecated ****************\n");
                        }
                        $unmatchedFlagged = false;
                    } else {
                        fwrite($fpNew, $line);
                    }
                    break;

                case 'other':
                default:
                    fwrite($fpNew, $line);
                    break;
            }
        }

        fclose($fpNew);
        fclose($fpCur);

        // If nothing changed, don't rename or remove anything.
        if ($changed) {
            // Remove any previous old copies: custom.config.ini.old
            // Rename current custom.config.ini to custom.config.ini.old
            // Rename new custom.config.ini.new to custom.config.ini
            $oldCopy = self::CUSTOM_CONFIG_FILE . ".old";
            if (file_exists($oldCopy)) {
                unlink($oldCopy);
            }

            rename(self::CUSTOM_CONFIG_FILE, $oldCopy);
            rename($filenameNew, self::CUSTOM_CONFIG_FILE);
        } elseif (file_exists($filenameNew)) {
            unlink($filenameNew);
        }
    }

}
