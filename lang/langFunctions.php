<?php
/**
 * Build array of defined languages
 * @param string $path Current process path
 * @return array
 */
function getDefinedLanguages(string $path): array
{
    // Open a known directory and proceed to read its contents
    if (!is_dir($path)) {
        exit("($path) is not a directory.");
    }

    $regxIterator = new RegexIterator(new DirectoryIterator($path), '/^[a-z]{2}(_[A-Z]{2})?$/');
    $langFiles = [];
    foreach ($regxIterator as $entry) {
        $langFiles[] = $entry->getFilename();
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
function processLangFile(string $path, string $langCode): array
{

    $langFile = file("$path/$langCode/lang.php");

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

