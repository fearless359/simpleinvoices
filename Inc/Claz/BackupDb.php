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
    public function __construct()
    {
        $this->output = "";

        $this->pdoDb = new PdoDb(new DbInfo(Config::CUSTOM_CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX));
    }

    /**
     * @param $filename
     * @throws PdoDbException
     */
    public function start_backup($filename): void
    {
        $fh = fopen($filename, "w");
        $rows = $this->pdoDb->query("SHOW TABLES");
        foreach ($rows as $row) {
            $this->show_create($row[0], $fh);
        }
        fclose($fh);
    }

    /**
     * @param $tableName
     * @param $fh
     * @throws PdoDbException
     */
    private function show_create($tableName, $fh): void
    {
        $query = "SHOW CREATE TABLE `$tableName`";
        $row = $this->pdoDb->query($query);
        fwrite($fh, $row[0][1] . ";\n");
        $insert = $this->retrieve_data($tableName);
        fwrite($fh, $insert);
        $this->output .= "<tr><td>Table: $tableName backed up successfully</td></tr>";
    }

    /**
     * @param $tableName
     * @return string
     * @throws PdoDbException
     */
    private function retrieve_data($tableName): string
    {
        $query = "SHOW COLUMNS FROM `{$tableName}`";
        $rows = $this->pdoDb->query($query);
        $i = 0;
        $columns = array();
        foreach($rows as $row) {
            $columns[$i++][0] = $row[0];
        }
        $colCnt = count($columns);
        $query = "";
        $rows = $this->pdoDb->request("SELECT", $tableName);
        foreach($rows as $row) {
            $query .= "INSERT INTO `{$tableName}` VALUES(";
            for ($i = 0; $i < $colCnt; $i++) {
                $query .= "'" . addslashes($row[$columns[$i][0]]) . "'" .
                         ($i + 1 == $colCnt ? ");\n" : ",");
            }
        }
        $query .= "\n";
        return $query;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}
