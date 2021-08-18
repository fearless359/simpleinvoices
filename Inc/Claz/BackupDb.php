<?php
namespace Inc\Claz;

/**
 * Class BackupDb
 * @package Inc\Claz
 */
class BackupDb {
    private string $output;

    /**
     * BackupDb constructor.
     */
    public function __construct()
    {
        $this->output = "";
    }

    /**
     * @param string $filename
     * @param PdoDb $pdoDb
     * @param array $LANG
     * @throws PdoDbException
     */
    public function startBackup(string $filename, PdoDb $pdoDb, array $LANG): void
    {
        $fileHandle = fopen($filename, "w");
        $rows = $pdoDb->query("SHOW TABLES");
        foreach ($rows as $row) {
            $this->showCreate($row[0], $fileHandle, $pdoDb, $LANG);
        }
        fclose($fileHandle);
    }

    /**
     * @param string $tableName
     * @param resource $fileHandle
     * @param PdoDb $pdoDb;
     * @param array $LANG
     * @throws PdoDbException
     */
    private function showCreate(string $tableName, $fileHandle, PdoDb $pdoDb, array $LANG): void
    {
        $query = "SHOW CREATE TABLE `$tableName`";
        $row = $pdoDb->query($query);
        fwrite($fileHandle, $row[0][1] . ";\n");
        $insert = $this->retrieveData($tableName, $pdoDb);
        fwrite($fileHandle, $insert);
        $this->output .= "<tr><td>{$LANG['tableUc']}: $tableName {$LANG['backedUpSuccessfully']}</td></tr>";
    }

    /**
     * @param string $tableName
     * @param PdoDb $pdoDb
     * @return string
     * @throws PdoDbException
     */
    private function retrieveData(string $tableName, PdoDb $pdoDb): string
    {
        $query = "SHOW COLUMNS FROM `$tableName`";
        $rows = $pdoDb->query($query);
        $idx = 0;
        $columns = [];
        foreach($rows as $row) {
            $columns[$idx++][0] = $row[0];
        }
        $colCnt = count($columns);
        $query = "";
        $rows = $pdoDb->request("SELECT", $tableName);
        foreach($rows as $row) {
            $query .= "INSERT INTO `$tableName` VALUES(";
            for ($idx = 0; $idx < $colCnt; $idx++) {
                $query .= "'" . addslashes($row[$columns[$idx][0]]) . "'" .
                         ($idx + 1 == $colCnt ? ");\n" : ",");
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
