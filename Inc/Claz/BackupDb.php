<?php
namespace Inc\Claz;

/**
 * Class BackupDb
 * @package Inc\Claz
 */
class BackupDb {
    private string $output;
    private PdoDb $pdoDb;

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
     * @param string $filename
     * @throws PdoDbException
     */
    public function startBackup(string $filename): void
    {
        $fileHandle = fopen($filename, "w");
        $rows = $this->pdoDb->query("SHOW TABLES");
        foreach ($rows as $row) {
            $this->showCreate($row[0], $fileHandle);
        }
        fclose($fileHandle);
    }

    /**
     * @param string $tableName
     * @param resource $fileHandle
     * @throws PdoDbException
     */
    private function showCreate(string $tableName, $fileHandle): void
    {
        $query = "SHOW CREATE TABLE `$tableName`";
        $row = $this->pdoDb->query($query);
        fwrite($fileHandle, $row[0][1] . ";\n");
        $insert = $this->retrieveData($tableName);
        fwrite($fileHandle, $insert);
        $this->output .= "<tr><td>Table: $tableName backed up successfully</td></tr>";
    }

    /**
     * @param string $tableName
     * @return string
     * @throws PdoDbException
     */
    private function retrieveData(string $tableName): string
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
            $query .= "INSERT INTO `$tableName` VALUES(";
            for ($i = 0; $i < $colCnt; $i++) {
                $query .= "'" . addslashes($row[$columns[$i][0]]) . "'" .
                         ($i + 1 == $colCnt ? ");\n" : ",");
            }
        }
        $query .= "\n";
        return $query;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
