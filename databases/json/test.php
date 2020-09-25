<?php

/**
 * Class Import
 */
class Import
{
    public string $file;
    public string $patternFind;
    public string $patternReplace;

    /**
     * @return false|string If "$result === false" then method failed. Otherwise
     *                      the string with file file contents is returned.
     */
    public function getFile()
    {
        return file_get_contents($this->file, true);
    }

    public function replace(): string
    {
        // $string = $this->decode( $this->getFile() );
        $string = $this->getFile();
        echo $string;
        echo "<br />####################<br />";
        // $replacements[0] = TB_PREFIX;
        $string = str_replace($this->patternFind, $this->patternReplace, $string);

        echo $string;
        return $string;
    }

    /**
     * @param $json
     * @return mixed
     */
    public function decode(string $json)
    {
        return json_decode($json, true);
    }

    public function process($aList)
    {
        foreach ($aList as $key => $val) {
            $table = $key;

            echo "<br>";
            echo "<b>Table: " . $table . "</b>";

            $columns = "";
            $values = "";
            foreach ($aList[$key] as $val2) {
                echo "<br>";
                $idx = "1";
                foreach ($val2 as $key3 => $val3) {

                    // TODO: IF NULL don't ''
                    $idx == "1" ? $columns .= $key3 : $columns .= ", " . $key3;
                    $idx == "1" ? $values .= "'" . $val3 . "'" : $values .= ", '" . $val3 . "'";

                    $idx++;
                }

                $sql = "INSERT into " . $table . " (" . $columns . ") VALUES (" . $values . ");";
                echo "SQL: " . $sql;
                $columns = "";
                $values = "";
            }
            echo "<br>";
        }
    }

    public function doImport()
    {
        $decode = $this->decode($this->getFile());
        $this->process($decode);
    }
}

$import = new Import();
$import->file = "EssentialData.json";
$import->patternFind = "si_";
$import->patternReplace = "XID";
$import->replace();
