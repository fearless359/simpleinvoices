<?php
/**
 * Build array of defined languages
 * @return array
 */
function getDefinedLanguages(): array
{
    // The root path of the language files. Change if needed.
    $dir = '.';

    // Open a known directory and proceed to read its contents
    if (!is_dir($dir)) {
        exit("($dir) is not a directory.");
    }

    $langFiles = [];

    //	Implementation - Forward Compatible
    try {
        foreach (new RegexIterator(new DirectoryIterator($dir), '/^[a-z]{2}(_[A-Z]{2})?$/') as $entry) {
            $langFiles[] = $entry->getFilename();
        }
    } catch (UnexpectedValueException $exp) {
        die($exp->getMessage());
    }

    // Sort by lang code.
    sort($langFiles);

    return $langFiles;
}


/**
 * Access a language folder and return array with two values:
 *  1) The total strings
 *  2) The total translated strings.
 * @param string $langCode
 * @return array
 */
function processLangFile(string $langCode): array
{

    $langFile = file("{$langCode}/lang.php");

    $count = 0;
    $countTranslated = 0;

    foreach ($langFile as $line) {
        $line = rtrim($line);

        // A string line
        if (preg_match('/^\$LANG\[/', $line)) {
            $count++;
        }
        // Each LANG string in one line only,
        // Accommodate multi-line strings with strict line ending.
        if (preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)) {
            $countTranslated++;
        }

    }

    return [$count, $countTranslated];
}

