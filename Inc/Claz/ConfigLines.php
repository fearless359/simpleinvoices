<?php

namespace Inc\Claz;

/**
 * Class ConfigLines
 * @package Inc\Claz
 */
class ConfigLines
{
    private string $section;
    private string $key;
    private string $value;

    /**
     * ConfigLines constructor.
     * @param string $section
     * @param string $key
     * @param string $value
     */
    public function __construct(string $section, string $key, string $value)
    {
        $this->section = $section;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Test line to see if section or key/value pair or other
     * @param string $line from file to test
     * @return string "other", "section" or "pair"
     */
    public static function lineType(string $line): string
    {
        $line = trim($line);
        $pattern = '/^\[.*\]$/';
        if (preg_match($pattern, $line) == 1) {
            return 'section';
        }

        $pattern = '/^[a-zA-Z0-9._]+[\t ]*=/';
        if (preg_match($pattern, $line) == 1) {
            return 'pair';
        }

        return 'other';
    }

    public function getSection(): string
    {
        return $this->section;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}