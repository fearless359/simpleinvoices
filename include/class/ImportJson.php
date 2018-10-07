<?php

/**
 * Class ImportJson
 */
class ImportJson {
    private $debug;
    private $file;
    private $find;
    private $replace;

    /**
     * ImportJson constructor.
     * @param string $file Json file to import.
     * @param array $find list of fields to find for replacement.
     * @param array $replace list of value to replace corresponding field in $find list.
     * @param bool $debug true if debug info to display, false (default) if not.
     */
    public function __construct($file, $find, $replace, $debug = false)
    {
        $this->debug = $debug;
        $this->file = $file;
        $this->find = $find;
        $this->replace = $replace;
    }

    public function getFile() {
        $json = file_get_contents($this->file, true);
        return $json;
    }

    public function replace($string) {
        $string_replaced = str_replace($this->find, $this->replace, $string);
        return $string_replaced;
    }

    public function decode($json) {
        $a = json_decode($json, true);
        return $a;
    }

    public function collate() {
        $json = $this->getFile();
        $replace = $this->replace($json);
        $decode = $this->decode($replace);
        return $this->process($decode);
    }

    public function process($a) {
        $sql = "";
        foreach($a as $k => $v) {
            $table = $k;

            if ($this->debug) echo "<br>";
            if ($this->debug) echo "<b>Table: " . $table . "</b>";

            $columns = "";
            $values = "";
            foreach($a[$k] as $v2) {
                if ($this->debug) echo "<br>";
                $i = "1";
                foreach($v2 as $k3 => $v3) {
                    $v3 = addslashes($v3); // Fix issue of single quotes used in input value.
                    // TODO: IF NULL don't ''
                    $i == "1" ? $columns .= $k3 : $columns .= ", " . $k3;
                    $i == "1" ? $values .= "'" . $v3 . "'" : $values .= ", '" . $v3 . "'";
                    $i++;
                }

                $sql_print = "INSERT into " . $table . " (" . $columns . ") VALUES (" . $values . ");";
                $sql .= $sql_print;
                if ($this->debug) echo "SQL: " . $sql_print;
                $columns = "";
                $values = "";
            }
            if ($this->debug) echo "<br>";
        }

        return $sql;
    }
}
