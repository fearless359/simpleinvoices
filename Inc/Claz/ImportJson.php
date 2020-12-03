<?php

namespace Inc\Claz;

/**
 * Class ImportJson
 */
class ImportJson
{
    private bool $debug;
    private string $fileName;
    private array $find;
    private array $replace;

    /**
     * ImportJson constructor.
     * @param string $fileName Json file to import.
     * @param array $find list of fields to find for replacement.
     * @param array $replace list of value to replace corresponding field in $find list.
     * @param bool $debug true if debug info to display, false (default) if not.
     */
    public function __construct(string $fileName, array $find, array $replace, bool $debug=false)
    {
        $this->debug = $debug;
        $this->fileName = $fileName;
        $this->find = $find;
        $this->replace = $replace;
    }

    /**
     * @return bool|string Read data or false if failure.
     */
    private function getFileContents()
    {
        return file_get_contents($this->fileName, true);
    }

    /**
     * @param string $str
     * @return mixed
     */
    private function replace(string $str)
    {
        return str_replace($this->find, $this->replace, $str);
    }

    /**
     * @param string $json
     * @return mixed
     * @noinspection PhpMethodMayBeStaticInspection
     */
    private function decode(string $json)
    {
        return json_decode($json, true);
    }

    /**
     * @return string
     */
    public function collate()
    {
        $json = $this->getFileContents();
        $replace = $this->replace($json);
        $decode = $this->decode($replace);
        return $this->process($decode);
    }

    private function process(array $aList): string
    {
        $sql = "";
        foreach ($aList as $key => $val) {
            $table = $key;

            if ($this->debug) {
                echo "<br>";
            }
            if ($this->debug) {
                echo "<b>Table: " . $table . "</b>";
            }

            $columns = "";
            $values = "";
            foreach ($aList[$key] as $val2) {
                if ($this->debug) {
                    echo "<br>";
                }
                $idx = "1";
                foreach ($val2 as $key3 => $val3) {
                    $val3 = addslashes($val3); // Fix issue of single quotes used in input value.
                    // TODO: IF null don't ''
                    $idx == "1" ? $columns .= $key3 : $columns .= ", " . $key3;
                    $idx == "1" ? $values .= "'" . $val3 . "'" : $values .= ", '" . $val3 . "'";
                    $idx++;
                }

                $sqlPrint = "INSERT into " . $table . " (" . $columns . ") VALUES (" . $values . ");";
                $sql .= $sqlPrint;
                if ($this->debug) {
                    echo "SQL: " . $sqlPrint;
                }
                $columns = "";
                $values = "";
            }
            if ($this->debug) {
                echo "<br>";
            }
        }

        return $sql;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFind(): array
    {
        return $this->find;
    }

    public function getReplace(): array
    {
        return $this->replace;
    }

}
