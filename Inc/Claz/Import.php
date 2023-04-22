<?php

namespace Inc\Claz;

/**
 * Class Import
 * @package Inc\Claz
 */
class Import
{
    public string $file;
    public bool $debug;
    /**
     * @var array|string
     */
    public $patternFind;

    /**
     * @var array|string
     */
    public $patternReplace;

    /**
     * @return bool|string Read data string or false on failure.
     */
    private function getFile()
    {
        return file_get_contents($this->file, true);
    }

    /**
     * @param string $string
     * @return string|array
     */
    public function replace(string $string)
    {
        return str_replace($this->patternFind, $this->patternReplace, $string);
    }

    /**
     * @return string|array
     */
    public function collate()
    {
        $json = $this->getFile();
        return $this->replace($json);
    }

}
