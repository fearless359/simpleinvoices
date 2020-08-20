<?php

namespace Inc\Claz;

use Exception;
use Zend_Config_Ini;
use Zend_Log;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

/**
 * Class Setup
 * @package Inc\Claz
 */
class Setup
{
    private DbInfo $dbInfo;
    private PdoDb $pdoDb;
    private PdoDb $pdoDbAdmin;
    private Zend_Config_Ini $zendConfigIni;

    /**
     * Setup constructor.
     * @param bool $updateCustomConfig
     * @throws PdoDbException
     * @throws Exception
     */
    public function __construct(bool $updateCustomConfig)
    {
        $this->zendConfigIni = Config::init(CONFIG_SECTION, $updateCustomConfig);

        $loggerLevel = isset($this->zendConfigIni->zend->logger_level) ? strtoupper($this->zendConfigIni->zend->logger_level) : 'EMERG';
        Log::open($loggerLevel);
        Log::out("Setup::init() - logger has been setup", Zend_Log::DEBUG);

        try {
            $this->dbInfo = new DbInfo(Config::CUSTOM_CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX);
            $this->pdoDb = new PdoDb($this->dbInfo);
            $this->pdoDbAdmin = new PdoDb($this->dbInfo);
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

        date_default_timezone_set($this->zendConfigIni->phpSettings->date->timezone);
        error_reporting($this->zendConfigIni->debug->error_reporting);

        ini_set('display_startup_errors', $this->zendConfigIni->phpSettings->display_startup_errors);
        ini_set('display_errors', $this->zendConfigIni->phpSettings->display_errors);
        ini_set('log_errors', $this->zendConfigIni->phpSettings->log_errors);
        ini_set('error_log', $this->zendConfigIni->phpSettings->error_log);

    }

    public function getConfig(): Zend_Config_Ini
    {
        return $this->zendConfigIni;
    }

    public function getDbInfo(): DbInfo
    {
        return $this->dbInfo;
    }

    public function getPdoDb(): PdoDb
    {
        return $this->pdoDb;
    }

    public function getPdoDbAdmin(): PdoDb
    {
        return $this->pdoDbAdmin;
    }

}