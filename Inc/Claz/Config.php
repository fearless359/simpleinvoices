<?php

namespace Inc\Claz;

use Exception;
use Zend_Config_Exception;
use Zend_Config_Ini;

/**
 * Class Config
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181009
 * @package Inc\Claz
 */
class Config
{
    public const CONFIG_FILE = "config/config.php";
    public const CUSTOM_CONFIG_FILE = "config/custom.config.php";

    private static Zend_Config_Ini $customConfig;
    private static string $versionName = "";
    private static string $versionUpdateDate = "";

    /**
     * Make sure we have a custom.config.php file that is consistent with the config.php file.
     * @param string $environment
     * @param bool $updateCustomConfig
     * @return Zend_Config_Ini
     * @throws Exception
     */
    public static function init(string $environment, bool $updateCustomConfig): Zend_Config_Ini
    {
        try {
            if ($updateCustomConfig) {
                self::makeCustomConfig();
                self::updateConfig();
            }
            self::$customConfig = new Zend_Config_Ini("./" . self::CUSTOM_CONFIG_FILE, $environment, true);
        } catch (Zend_Config_Exception $zce) {
            SiError::out('generic', 'Zend_Config_Ini', $zce->getMessage());
        }
        return self::$customConfig;
    }

    /**
     * Make sure we have a custom.config.php file. Copy config.php is needed.
     */
    private static function makeCustomConfig(): void
    {
        // Create custom.config.php file if it doesn't already exist
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

        $changes = self::genChanges($configInfo, $customConfigInfo);

        self::updateCustomConfig($changes);
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
        $newitems = [];
        $olditems = [];

        // The key is:  section|key_part_of_value_pair
        // The value is: ConfigLines object
        foreach ($configInfo as $key => $value) {
            $parts = explode('|', $key);
            if (!isset($customConfigInfo[$key])) {
                $newitems[$parts[0]][$parts[1]] = $value;
            }

            if ($parts[0] == 'production') {
                if ($parts[1] == 'version.name') {
                    self::$versionName = $value->getValue();
                } elseif ($parts[1] == 'version.update_date') {
                    self::$versionUpdateDate = $value->getValue();
                }
            }
        }

        foreach ($customConfigInfo as $key => $value) {
            if (!isset($configInfo[$key])) {
                $parts = explode('|', $key);
                $olditems[$parts[0]][$parts[1]] = $value;
            }
        }


        return [
            'new' => $newitems,
            'old' => $olditems
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
        $newpairs = $changes['new'];
        $changed = false;

        $filenameNew = './' . self::CUSTOM_CONFIG_FILE . ".new";
        $fnew = fopen($filenameNew, 'w');
        if ($fnew === false) {
            die("Config::updateCustomConfig() - Unable to open new './" . self::CUSTOM_CONFIG_FILE . ".new' file");
        }

        $fcur = fopen('./' . self::CUSTOM_CONFIG_FILE, 'r');
        if ($fcur === false) {
            die("Config::updateCustomConfig() - Unable to open './" . self::CUSTOM_CONFIG_FILE . "'");
        }
        $section = null;
        $unmatchedFlagged = false;
        while (($line = fgets($fcur)) !== false) {
            if (!$unmatchedFlagged) {
                $unmatchedFlagged = preg_match('/.*Possibly Deprecated/', $line);
            }

            switch (ConfigLines::lineType($line)) {
                case 'section':
                    fwrite($fnew, $line);
                    $pattern = '/^[\t ]*\[(.*)\].*$/';
                    $section = trim(preg_replace($pattern, '$1', $line));
                    // Write out all new lines for this section
                    if (isset($newpairs[$section])) {
                        $changed = true;
                        foreach ($newpairs[$section] as $newpair) {
                            settype($newpair, 'object');
                            $newline = $newpair->getKey() . ' = ' . $newpair->getValue() . "\n";
                            fwrite($fnew, $newline);
                        }
                    }
                    break;

                case 'pair':
                    $pattern = '/^[\t ]*([a-zA-Z0-9._]+)[\t ]*=.*$/';
                    $key = trim(preg_replace($pattern, '$1', $line));
                    if (!isset($section)) {
                        fclose($fnew);
                        fclose($fcur);
                        throw new Exception("Config::updateCustomConfig() - Key/pair ($line) found prior to section being set.");
                    }

                    if ($key == 'version.name') {
                        $replPattern = '${1}' . self::$versionName;
                        $newline = preg_replace('/^(.*= ).*$/', $replPattern, $line);
                        if (!$changed) {
                            $changed = $newline != $line;
                        }
                        fwrite($fnew, $newline);
                    } elseif ($key == 'version.update_date') {
                        $replPattern = '${1}' . self::$versionUpdateDate;
                        $newline = preg_replace('/^(.*= *).*$/', $replPattern, $line);
                        if (!$changed) {
                            $changed = $newline != $line;
                        }
                        fwrite($fnew, $newline);
                    } elseif (isset($unmatched[$section][$key])) {
                        if (!$unmatchedFlagged) {
                            $changed = true;
                            fwrite($fnew, ";******** Possibly Deprecated - not in config.php ********\n");
                        }
                        fwrite($fnew, $line);
                        if (!$unmatchedFlagged) {
                            fwrite($fnew, ";**************** End Possibly Deprecated ****************\n");
                        }
                        $unmatchedFlagged = false;
                    } else {
                        fwrite($fnew, $line);
                    }
                    break;

                case 'other':
                default:
                    fwrite($fnew, $line);
                    break;
            }
        }

        fclose($fnew);
        fclose($fcur);

        // If nothing changed, don't rename or remove anything.
        if ($changed) {
            // Remove any previous old copies: custom.config.php.old
            // Rename current custom.config.php to custom.config.php.old
            // Rename new custom.config.php.new to custom.config.php
            $oldcopy = self::CUSTOM_CONFIG_FILE . ".old";
            if (file_exists($oldcopy)) {
                unlink($oldcopy);
            }

            rename(self::CUSTOM_CONFIG_FILE, $oldcopy);
            rename($filenameNew, self::CUSTOM_CONFIG_FILE);
        } elseif (file_exists($filenameNew)) {
            unlink($filenameNew);
        }
    }

}
