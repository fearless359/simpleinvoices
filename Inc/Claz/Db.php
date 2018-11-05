<?php
namespace Inc\Claz;

class Db {
    private $_db;
    private $_pdoAdapter;
    private static $_instance;

    // public $connection = null;
    function __construct() {
        global $config;

        // check if PDO is available
        if (!class_exists('PDO', false)) {
            SiError::out("PDO");
        }

        // Strip the pdo_ section from the adapter
        $this->_pdoAdapter = substr($config->database->adapter, 4);
        if ($this->_pdoAdapter != "mysql") {
            SiError::out("PDO_not_mysql");
        }

        // @formatter:off
        try {
            $this->_db = new \PDO(
                    'mysql:host=' . $config->database->params->host . '; ' .
                    'port='       . $config->database->params->port . '; ' .
                    'dbname='     . $config->database->params->dbname,
                                    $config->database->params->username,
                                    $config->database->params->password,
                                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"));
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            SiError::out("dbConnection", $exception->getMessage());
            die($exception->getMessage());
        }
    }

    // Instantiate the class object if it isn't already instantiated.
    // Otherwise return the existing instance.
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function query($sqlQuery) {
        try {
            $argc = func_num_args();
            $binds = func_get_args();
            $sth = $this->_db->prepare($sqlQuery);
            if ($argc > 1) {
                array_shift($binds);
                for ($i = 0; $i < count($binds); $i++) {
                    $sth->bindValue($binds[$i], $binds[++$i]);
                }
            }

            $sth->execute();
            if ($sth->errorCode() > '0') {
                SiError::out('sql', $sth->errorInfo(), $sqlQuery);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo "Not sure what happened with your query?:<br /><br /> " .
                  htmlsafe($sqlQuery) . "<br />" .
                  htmlsafe(end($this->_db->errorInfo()));
            $sth = NULL;
        }
        return $sth;
    }
}
