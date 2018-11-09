<?php
namespace Inc\Claz;

/**
 * Class Import
 * @package Inc\Claz
 */
class Import {
    public $file;
    public $debug;
    public $pattern_find;
    public $pattern_replace;

    /**
     * @return bool|string
     */
    private function getFile() {
        $json = file_get_contents($this->file, true);
        return $json;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function replace($string) {
        $string_replaced = str_replace($this->pattern_find, $this->pattern_replace, $string);
        
        return $string_replaced;
    }

    /**
     * @return mixed
     */
    public function collate() {
        $json = $this->getFile();
        $replace = $this->replace($json);
        return $replace;
    }
}
