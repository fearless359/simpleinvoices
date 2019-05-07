<?php
/**
 * @name Setup.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181115
 */

namespace Inc\Claz;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

class Setup
{
    /**
     * Add directories to include files from to the path.
     * TODO: Use namespace for all and eliminate this.
     */
    public static function setPath()
    {
        $lcl_path = get_include_path() .
            PATH_SEPARATOR . "./library/" .
            PATH_SEPARATOR . "./include/";
        if (set_include_path($lcl_path) === false) {
            error_log("Error reported by set_include_path() for path: {$lcl_path}");
        }
    }

    /**
     * @param bool $updateCustomConfig true if you want custom.config.php updated with new
     *              values from config.php. false if it should not be updated.
     * @param object &$config
     * @param DbInfo &$dbInfo
     * @param PdoDb &$pdoDb
     * @param PdoDb &$pdoDb_admin
     * @throws PdoDbException
     */
    public static function init($updateCustomConfig, &$config, &$dbInfo, &$pdoDb, &$pdoDb_admin)
    {
        try {
            $config = Config::init(CONFIG_SECTION, $updateCustomConfig);
        } catch (\Exception $e) {
            echo "<h1 style='font-weight:bold;color:red;'>";
            echo "  " . $e->getMessage() . " (Error code: {$e->getCode()})";
            echo "</h1>";
        }

        $logger_level = (isset($config->zend->logger_level) ? strtoupper($config->zend->logger_level) : 'EMERG');
        Log::open($logger_level);
        Log::out("Setup::init() - logger has been setup", \Zend_Log::DEBUG);

        try {
            $dbInfo = new DbInfo(Config::CUSTOM_CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX);

            $pdoDb = new PdoDb($dbInfo);
            $pdoDb->clearAll(); // to eliminate never used warning.

            // For use by admin functions only. This avoids issues of
            // concurrent use with user app object, <i>$pdoDb</i>.
            $pdoDb_admin = new PdoDb($dbInfo);
            $pdoDb_admin->clearAll();
        } catch (PdoDbException $pde) {
            if (preg_match('/.*{dbname|password|username}/', $pde->getMessage())) {
                echo "<h1 style='font-weight:bold;color:red;'>Initial setup. Follow the following instructions:</h1>";
                echo "<ol>";
                echo "  <li>Make a mySQL compatible database with a user that has full access to it.</li>";
                echo "  <li>In the \"config\" directory, copy the <b>config.php</b> file to <b>custom.config.php</b></li>";
                echo "  <li>Modify the database settings in the <b>custom.config.php</b> file for the database made in step 1.";
                echo "    <ul>";
                echo "      <li>Set <b>database.params.dbname</b> to the name of the database.";
                echo "      <li>Set <b>database.params.username</b> to the username of the database administrator.</li>";
                echo "      <li>Set <b>database.params.password</b> to the database administrator password. Note you might need to include this in single quotes.</li>";
                echo "    </ul>";
                echo "  </li>";
                echo "  <li>In your browser, execute the command to access SI again and follow the instructions.</li>";
                echo "</ol>";
            } else {
                echo "<h1 style='font-weight:bold;color:red;'>";
                echo "  " . $pde->getMessage() . " (Error code: {$pde->getCode()})";
                echo "</h1>";
            }

            throw new PdoDbException($pde->getMessage());
        }

        date_default_timezone_set($config->phpSettings->date->timezone);
        error_reporting($config->debug->error_reporting);

        ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);
        ini_set('display_errors',         $config->phpSettings->display_errors);
        ini_set('log_errors',             $config->phpSettings->log_errors);
        ini_set('error_log',              $config->phpSettings->error_log);
    }
}