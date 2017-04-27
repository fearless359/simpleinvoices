<?php
require_once 'include/class/PdoDb.php';

class myBackupDb extends BackupDb {
    private $output;
    private $pdoDb;
    private $skipe = array();

    public function __construct() {
        $this->output = "";

        global $config;
        // @formatter:off
        $type = substr($config->database->adapter, 4);
        $host = $config->database->params->host . ':' .
                $config->database->params->port;
        $name = $config->database->params->dbname;
        $user = $config->database->params->username;
        $pwrd = $config->database->params->password;
        // @formatter:on
        $this->pdoDb = new PdoDb(new DbInfo($type, $name, $host, $pwrd, $user));
error_log("BackupDb::__construct");//.new PdoDb(new DbInfo(type($type), name($name), host($host), pwrd($pwrd), user($user)))");
    }

	public function skipit($tablename)
	{
		if (in_array($tablename, $this->skipe)) {
error_log('already skipping '. $tablename);
			return false;
		}
		$this->skipe[] = $tablename;
//error_log('skipping '. $tablename);//. '('. print_r($this->skipe, true). ')');
	}

    public function start_backup($filename) {
        $fh = fopen($filename, "w");
	$array = array();
        $rows = $this->pdoDb->query("SHOW TABLES");
//	error_log('BackupDb::start_backup:');
        foreach ($rows as $row)	// convert md array to basic array
	{
		$array[] = $row[0];
	}
	$dotbls = array_diff($array, $this->skipe);	// take away the tables to skip
//error_log('start_backup-dotbls:'. print_r($dotbls, true));
        foreach ($dotbls as $a)
	{
//error_log('start_backup-do table:'. $a);
		$this->show_create($a, $fh);	// add sql to file (main backup)
	}
	if (count($this->skipe))	// if skipped tables, add to output
	{
		foreach ($this->skipe as $s)
		{
//error_log('start_backup-skipped:'. $s);
			$this->output[] = array('table' => $s, 'status' => 'skipped');
		}
		sort($this->output);	// sort tables a..z
	}
        fclose($fh);
    }

    private function show_create($tablename, $fh) {
        $query = "SHOW CREATE TABLE `$tablename`";
        $row = $this->pdoDb->query($query);
        fwrite($fh, $row[0][1] . ";\n");
        $insert = $this->retrieve_data($tablename);
        fwrite($fh, $insert);
        $this->output[] = array('table' => $tablename, 'status' => 'success');
//	error_log('BackupDb::show_create['.$this->output.'].');
    }

    private function retrieve_data($tablename) {
        $query = "SHOW COLUMNS FROM `" . $tablename . "`";
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
            $query .= "INSERT INTO `" . $tablename . "` VALUES(";
            for ($i = 0; $i < $colcnt; $i++) {
                $query .= "'" . addslashes($row[$columns[$i][0]]) . "'" .
                         ($i + 1 == $colcnt ? ");\n" : ",");
            }
        }
// 	error_log('BackupDb::retrieve_data['.$this->output.'].');
        $query .= "\n";
        return $query;
    }

    public function getOutput() {
        return $this->output;
    }
}
