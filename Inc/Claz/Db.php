<?php
/** @noinspection PhpClassNamingConventionInspection */

namespace Inc\Claz;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Class Db
 * @package Inc\Claz
 */
class Db
{
    private static Db $db;

    private PDO $pdo;

    private string $dbname;
    private string $dsn;
    private string $host;
    private string $password;
    private string $pdoAdapter;
    private string $port;
    private string $userName;

    private ?array $utf8;

    /**
     * Db constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        // check if PDO is available
        if (!class_exists('PDO', false)) {
            SiError::out("PDO");
        }

        $this->dbname = $config['databaseDbname'];
        $this->host = $config['databaseHost'];
        $this->password = $config['databasePassword'];
        $this->pdoAdapter = self::extractAdapter($config['databaseAdapter']);
        $this->port = $config['databasePort'];
        $this->userName = $config['databaseUsername'];
        $this->utf8 = self::setUtf8($config['databaseUtf8']);

        $this->dsn = $this->genDsn();

        $this->instantiatePdo();
    }

    private static function extractAdapter(string $databaseAdapter): string
    {
        // Strip the pdo_ section from the adapter
        if ($databaseAdapter != "pdo_mysql") {
            SiError::out("PDO_not_mysql");
        }
        return substr($databaseAdapter, 4);
    }

    private function genDsn(): string
    {
        return "$this->pdoAdapter:host=$this->host; port=$this->port; dbname=$this->dbname;";
    }

    /**
     * Instantiate the class object if it isn't already instantiated.
     * Otherwise return the existing instance.
     * @param array $config config.ini / custom.config.ini values
     * @return Db
     */
    public static function getInstance(array $config): Db
    {
        if (!isset(self::$db)) {
            self::$db = new self($config);
        }
        return self::$db;
    }

    /**
     * @param bool $utf8
     * @return string[]|null
     */
    private static function setUtf8(bool $utf8): ?array
    {
        return $utf8 ? [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8;'] : null;
    }

    private function instantiatePdo(): void
    {
        // @formatter:off
        try {
            $this->pdo = new PDO($this->dsn, $this->userName, $this->password, $this->utf8);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            SiError::out("dbConnection", $exception->getMessage());
            exit($exception->getMessage());
        }
    }

    /**
     * @param string $sqlQuery
     * @return object|null|PDOStatement
     */
    public function query(string $sqlQuery): ?object
    {
        try {
            $argc = func_num_args();
            $binds = func_get_args();
            $sth = $this->pdo->prepare($sqlQuery);
            if ($argc > 1) {
                array_shift($binds);
                for ($idx = 0; $idx < count($binds); $idx++) {
                    $sth->bindValue($binds[$idx], $binds[++$idx]);
                }
            }

            $sth->execute();
            if ($sth->errorCode() > '0') {
                $val = $sth->errorInfo();
                SiError::out('sql', $val[2], $sqlQuery);
            }
        } catch (Exception $exp) {
            echo $exp->getMessage();
            $val = $this->pdo->errorInfo();
            echo "Not sure what happened with your query?:<br /><br /> " .
                  Util::htmlSafe($sqlQuery) . "<br />" .
                  Util::htmlSafe($val[2]);
            $sth = null;
        }
        return $sth;
    }
}
