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
    public string|array $patternFind;
    public string|array $patternReplace;

    /**
     * @return bool|string Read data string or false on failure.
     */
    private function getFile(): bool|string
    {
        return file_get_contents($this->file, true);
    }

    public function replace(string $string): array|string
    {
        return str_replace($this->patternFind, $this->patternReplace, $string);
    }

    public function collate(): array|string
    {
        $json = $this->getFile();
        return $this->replace($json);
    }
}
