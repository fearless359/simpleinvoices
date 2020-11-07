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
     * @throws PdoDbException
     */
    public function __construct()
    {
        $this->output = "";
    }

    /**
     * @param string $filename
     * @throws PdoDbException
     */
    public function startBackup(string $filename): void
    {
        global $pdoDb;

        $fileHandle = fopen($filename, "w");
        $rows = $pdoDb->query("SHOW TABLES");
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
        global $LANG, $pdoDb;

        $query = "SHOW CREATE TABLE `$tableName`";
        $row = $pdoDb->query($query);
        fwrite($fileHandle, $row[0][1] . ";\n");
        $insert = $this->retrieveData($tableName);
        fwrite($fileHandle, $insert);
        $this->output .= "<tr><td>{$LANG['tableUc']}: $tableName {$LANG['backedUpSuccessfully']}</td></tr>";
    }

    /**
     * @param string $tableName
     * @return string
     * @throws PdoDbException
     */
    private function retrieveData(string $tableName): string
    {
        global $pdoDb;

        $query = "SHOW COLUMNS FROM `{$tableName}`";
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
