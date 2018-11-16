<?php
namespace Inc\Claz;

/**
 * Class BackupDb
 * @package Inc\Claz
 */
class BackupDb {
    private $output;
    private $pdoDb;

    /**
     * BackupDb constructor.
     * @throws PdoDbException
     */
    public function __construct() {
        $this->output = "";

        $this->pdoDb = new PdoDb(new DbInfo(Config::CUSTOM_CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX));
    }

    /**
     * @param $filename
     * @throws PdoDbException
     */
    public function start_backup($filename) {
        $fh = fopen($filename, "w");
        $rows = $this->pdoDb->query("SHOW TABLES");
        foreach ($rows as $row) {
            $this->show_create($row[0], $fh);
        }
        fclose($fh);
    }

    /**
     * @param $tablename
     * @param $fh
     * @throws PdoDbException
     */
    private function show_create($tablename, $fh) {
        $query = "SHOW CREATE TABLE `$tablename`";
        $row = $this->pdoDb->query($query);
        fwrite($fh, $row[0][1] . ";\n");
        $insert = $this->retrieve_data($tablename);
        fwrite($fh, $insert);
        $this->output .= "<tr><td>Table: $tablename backed up successfully</td></tr>";
    }

    /**
     * @param $tablename
     * @return string
     * @throws PdoDbException
     */
    private function retrieve_data($tablename) {
        $query = "SHOW COLUMNS FROM `{$tablename}`";
        $rows = $this->pdoDb->query($query);
        $i = 0;
        $columns = array();
        foreach($rows as $row) {
            $columns[$i++][0] = $row[0];
        }
        $colcnt = count($columns);
        $query = "";
        $rows = $this->pdoDb->request("SELECT", $tablename);
        foreach($rows as $row) {
            $query .= "INSERT INTO `{$tablename}` VALUES(";
            for ($i = 0; $i < $colcnt; $i++) {
                $query .= "'" . addslashes($row[$columns[$i][0]]) . "'" .
                         ($i + 1 == $colcnt ? ");\n" : ",");
            }
        }
        $query .= "\n";
        return $query;
    }

    /**
     * @return string
     */
    public function getOutput() {
        return $this->output;
    }
}
