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
    private static Db $instance;

    private PDO $db;
    private string $pdoAdapter;

    // public $connection = null;
    public function __construct()
    {
        global $config;

        // check if PDO is available
        if (!class_exists('PDO', false)) {
            SiError::out("PDO");
        }

        // Strip the pdo_ section from the adapter
        $this->pdoAdapter = substr($config['databaseAdapter'], 4);
        if ($this->pdoAdapter != "mysql") {
            SiError::out("PDO_not_mysql");
        }

        // @formatter:off
        try {
            $this->db = new PDO(
                    'mysql:host=' . $config['databaseHost'] . '; ' .
                    'port='       . $config['databasePort'] . '; ' .
                    'dbname='     . $config['databaseDbname'],
                                    $config['databaseUsername'],
                                    $config['databasePassword'],
                                    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"]);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            SiError::out("dbConnection", $exception->getMessage());
            die($exception->getMessage());
        }
    }

    /**
     * Instantiate the class object if it isn't already instantiated.
     * Otherwise return the existing instance.
     * @return Db
     */
    public static function getInstance(): Db
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $sqlQuery
     * @return bool|null|PDOStatement
     */
    public function query($sqlQuery)
    {
        try {
            $argc = func_num_args();
            $binds = func_get_args();
            $sth = $this->db->prepare($sqlQuery);
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
            $val = $this->db->errorInfo();
            echo "Not sure what happened with your query?:<br /><br /> " .
                  Util::htmlSafe($sqlQuery) . "<br />" .
                  Util::htmlSafe($val[2]);
            $sth = null;
        }
        return $sth;
    }
}
