<?php

use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;

/*
 * Read language information
 * 1. reads default-language file
 * 2. reads requested language file
 * 3. make some editing (Upper-Case etc.)
 * Not in each translated file need to be each all translations, only in the default-lang-file (english)
 */
global $LANG, $databaseBuilt, $pdoDb;
unset($LANG);
$LANG = [];

if ($databaseBuilt) {
    $found = false;
    try {
        $tables = $pdoDb->query("SHOW TABLES");

        // if upgrading from old version then getDefaultLang wont work during install
        $tbl = TB_PREFIX . 'system_defaults';
        foreach ($tables as $table) {
            if ($table[0] == $tbl) {
                $found = true;
                break;
            }
        }
    } catch (PdoDbException $pde) {
        error_log("language.php: DB error performing SHOW TABLES. Error: " . $pde->getMessage());
    }

    if ($found) {
        $language = SystemDefaults::getLanguage();
    } else {
        $language = "en_US";
    }
} else {
    $language = "en_US";
}

/**
 * Update global array for language elements from all pertinent sources.
 * @param string $lang
 * @return array
 */
function getLanguageArray(string $lang = ''): array
{
    global $extNames, $LANG;

    if (!empty($lang)) {
        $language = $lang;
    } else {
        global $language;
    }

    $langPath = "lang/";
    $langFile = "/lang.php";
    include($langPath . "en_US" . $langFile);
    if (file_exists($langPath . $language . $langFile)) {
        include($langPath . $language . $langFile);
    }

    foreach ($extNames as $extName) {
        if (file_exists("extensions/$extName/lang/$language/lang.php")) {
            include_once "extensions/$extName/lang/$language/lang.php";
        }
    }

    return $LANG;
}

function getLanguageList(): array
{
    $xmlFile = "info.xml";
    $langPath = "lang/";
    $folders = null;

    if ($handle = opendir($langPath)) {
        for ($idx = 0; $file = readdir($handle); $idx++) {
            $folders[$idx] = $file;
        }
        closedir($handle);
    }

    $languages = [];
    $idx = 0;

    foreach ($folders as $folder) {
        $file = $langPath . $folder . "/" . $xmlFile;
        if (file_exists($file)) {
            $values = simplexml_load_file($file);
            $languages[$idx] = $values;
            $idx++;
        }
    }

    return $languages;
}

$LANG = getLanguageArray();
