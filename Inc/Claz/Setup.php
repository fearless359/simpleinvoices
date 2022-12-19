<?php

namespace Inc\Claz;

use Exception;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

/**
 * Class Setup
 * @package Inc\Claz
 */
class Setup
{
    private PdoDb $pdoDb;
    private PdoDb $pdoDbAdmin;
    private array $configIni;

    /**
     * Setup constructor.
     * @param string|null $environment
     * @param bool $updateCustomConfig
     * @param string $configFile
     * @throws PdoDbException
     * @throws Exception
     */
    public function __construct(?string $environment, bool $updateCustomConfig, string $configFile = Config::CUSTOM_CONFIG_FILE)
    {
        $this->configIni = Config::init($environment, $updateCustomConfig, $configFile);
        $loggerLevel = isset($this->configIni['loggerLevel']) ? strtoupper($this->configIni['loggerLevel']) : 'EMERGENCY';
        Log::open($loggerLevel);
        Log::out("Setup::init() - logger opened. loggerLevel[$loggerLevel]");

        // If not CUSTOM_CONFIG_FILE, this is a test setup. So do not instantiate database objects.
        if ($configFile == Config::CUSTOM_CONFIG_FILE) {
            try {
                $this->pdoDb = new PdoDb($this->configIni);
                $this->pdoDbAdmin = new PdoDb($this->configIni);
            } catch (PdoDbException $pde) {
                SiError::out('dbConnection', $pde->getMessage());
                throw new PdoDbException($pde->getMessage());
            }
        }

        date_default_timezone_set($this->configIni['phpSettingsDateTimezone']);
        error_reporting($this->configIni['debugErrorReporting']);

        ini_set('display_startup_errors', $this->configIni['phpSettingsDisplayStartupErrors']);
        ini_set('display_errors', $this->configIni['phpSettingsDisplayErrors']);
        ini_set('log_errors', $this->configIni['phpSettingsLogErrors']);
        ini_set('error_log', $this->configIni['phpSettingsErrorLog']);
    }

    public function getConfigIni(): array
    {
        return $this->configIni;
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
