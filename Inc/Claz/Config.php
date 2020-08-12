<?php

namespace Inc\Claz;

use Exception;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * Class Config
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181009
 * @package Inc\Claz
 */
class Config
{
    const CONFIG_FILE = "config/config.php";
    const CUSTOM_CONFIG_FILE = "config/custom.config.php";

    /** @var object|null  */
    private static $custom_config = null;
    /** @var string|null  */
    private static $version_name = null;
    /** @var string|null  */
    private static $version_update_date = null;

    /**
     * Make sure we have a custom.config.php file that is consistent with the config.php file.
     * @param string $environment
     * @param bool $updateCustomConfig
     * @return object Zend_Config_Ini class object
     * @throws Exception
     */
    public static function init(string $environment, bool $updateCustomConfig): object
    {
        try {
            if ($updateCustomConfig) {
                self::makeCustomConfig();
                self::updateConfig();
            }
            self::$custom_config = new \Zend_Config_Ini("./" . self::CUSTOM_CONFIG_FILE, $environment, true);
        } catch (\Zend_Config_Exception $zce) {
            SiError::out('generic', 'Zend_Config_Ini', $zce->getMessage());
        }
        return self::$custom_config;
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

        $config_info = self::loadFileInfo(self::CONFIG_FILE, $fp);
        fclose($fp);

        $fp = fopen('./' . self::CUSTOM_CONFIG_FILE, 'r');
        if ($fp === false) {
            SiError::out('generic', 'Config::update_config()', 'Unable to open ' . self::CUSTOM_CONFIG_FILE);
        }

        $custom_config_info = self::loadFileInfo(self::CUSTOM_CONFIG_FILE, $fp);
        fclose($fp);

        $changes = self::genChanges($config_info, $custom_config_info);

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
        $info = array();
        $section = null;
        while (($line = fgets($fp)) !== false) {
            switch (ConfigLines::line_type($line)) {
                case 'section':
                    $section = trim(preg_replace('/^\[(.*)\]/', '$1', $line));
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
     * @param array $config_info standard config file that has all items for SI.
     * @param array $custom_config_info user custom configuration items list.
     * @return array configuration items to be added or removed from custom config.
     *      The returned array contains two associative entries, "new" and "old".
     *      The "new" entries need to be added to the custom config file. The "old"
     *      entries need to be marked as not in standard config file.
     */
    private static function genChanges(array $config_info, array $custom_config_info): array
    {
        $newitems = array();
        $olditems = array();

        // The key is:  section|key_part_of_value_pair
        // The value is: ConfigLines object
        foreach ($config_info as $key => $value) {
            $parts = explode('|', $key);
            if (!isset($custom_config_info[$key])) {
                $newitems[$parts[0]][$parts[1]] = $value;
            }

            if ($parts[0] == 'production') {
                if ($parts[1] == 'version.name') {
                    self::$version_name = $value->getValue();
                } else if ($parts[1] == 'version.update_date') {
                    self::$version_update_date = $value->getValue();
                }
            }
        }

        foreach ($custom_config_info as $key => $value) {
            if (!isset($config_info[$key])) {
                $parts = explode('|', $key);
                $olditems[$parts[0]][$parts[1]] = $value;
            }
        }

        $results = array('new' => $newitems, 'old' => $olditems);
        return $results;
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

        $filename_new = './' . self::CUSTOM_CONFIG_FILE . ".new";
        $fnew = fopen($filename_new, 'w');
        if ($fnew === false) {
            die("Config::updateCustomConfig() - Unable to open new './" . self::CUSTOM_CONFIG_FILE . ".new' file");
        }

        $fcur = fopen('./' . self::CUSTOM_CONFIG_FILE, 'r');
        if ($fcur === false) {
            die("Config::updateCustomConfig() - Unable to open './" . self::CUSTOM_CONFIG_FILE . "'");
        }
        $section = null;
        $unmatched_flagged = false;
        while (($line = fgets($fcur)) !== false) {
            if (!$unmatched_flagged) {
                $unmatched_flagged = (preg_match('/.*Possibly Deprecated/', $line));
            }

            switch (ConfigLines::line_type($line)) {
                case 'section':
                    fwrite($fnew, $line);
                    $section = trim(preg_replace('/^[\t ]*\[(.*)\].*$/', '$1', $line));
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
                    $key = trim(preg_replace('/^[\t ]*([a-zA-Z0-9._]+)[\t ]*=.*$/', '$1', $line));
                    if (!isset($section)) {
                        fclose($fnew);
                        fclose($fcur);
                        throw new Exception("Config::updateCustomConfig() - Key/pair ($line) found prior to section being set.");
                    }

                    if ($key == 'version.name') {
                        $repl_pattern = '${1}' . self::$version_name;
                        $newline = preg_replace('/^(.*= ).*$/', $repl_pattern, $line);
                        if (!$changed) {
                            $changed = ($newline != $line);
                        }
                        fwrite($fnew, $newline);
                    } else if ($key == 'version.update_date') {
                        $repl_pattern = '${1}' . self::$version_update_date;
                        $newline = preg_replace('/^(.*= *).*$/', $repl_pattern, $line);
                        if (!$changed) {
                            $changed = ($newline != $line);
                        }
                        fwrite($fnew, $newline);
                    } else if (isset($unmatched[$section][$key])) {
                        if (!$unmatched_flagged) {
                            $changed = true;
                            fwrite($fnew, ";******** Possibly Deprecated - not in config.php ********\n");
                        }
                        fwrite($fnew, $line);
                        if (!$unmatched_flagged) {
                            fwrite($fnew, ";**************** End Possibly Deprecated ****************\n");
                        }
                        $unmatched_flagged = false;
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
            rename($filename_new, self::CUSTOM_CONFIG_FILE);
        } else if (file_exists($filename_new)) {
            unlink($filename_new);
        }
    }

}

class ConfigLines
{
    /** @var string  */
    private $section;
    /** @var string  */
    private $key;
    /** @var string  */
    private $value;

    /**
     * ConfigLines constructor.
     * @param string $section
     * @param string $key
     * @param string $value
     */
    public function __construct(string $section, string $key, string $value)
    {
        $this->section = $section;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Test line to see if section or key/value pair or other
     * @param string $line from file to test
     * @return string "other", "section" or "pair"
     */
    public static function line_type(string $line): string
    {
        $line = trim($line);
        if (preg_match('/^\[.*\]$/', $line) == 1) return 'section';
        if (preg_match('/^[a-zA-Z0-9._]+[\t ]*=/', $line) == 1) return 'pair';
        return 'other';
    }

    /**
     * @return string section
     */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @return string key
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string Value
     */
    public function getValue(): string
    {
        return $this->value;
    }

}