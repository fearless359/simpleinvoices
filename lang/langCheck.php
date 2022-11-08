<?php
/*
 * Script: langCheck.php
 *       Calculate languages translation status
 *
 * Authors:
 *        Rui Gouveia
 *
 * Last edited:
 *        2007-11-01, 2008-01-26, 2008-05-12, 2008-08-28
 *
 * License:
 *        GPL v3
 *
 * Usage:
 *      To execute the lang check run the command below
 *      and view the langCheck.html file in your browser
 *
 *      php.exe -q lang/langCheck.php > lang/langCheck.txt
 *
 */


/*
 * Get the language codes ('en', 'pt', etc.) that exists in this folder.
 */
const SI_DEBUG = false;
const SI_AUTHOR = false;
$uLine = 106;
if (SI_AUTHOR) {
    $uLine += 11;
}
$path = dirname(__FILE__);
include_once "{$path}/langFunctions.php";

// Header
echo str_repeat("=", $uLine);
echo "\n";
echo sprintf("%-10s", 'Lang. Code') . " | ";
echo sprintf("%-29s", 'Lang. name') . " | ";
echo sprintf("%-11s", 'New strings') . " | ";
echo sprintf("%15s", 'Strings in file') . " | ";
echo sprintf("%16s", 'Total translated') . " | ";
echo sprintf("%8s", '% Done') . " | ";
if (SI_AUTHOR) {
    echo sprintf("%10s", 'Authors');
}
echo "\n";
echo str_repeat("=", $uLine);
echo "\n";

// The main language. Needed to compare the % done of the other languages.
$enLang = processLangFile($path,'en_US');
if (SI_DEBUG) {
    echo "debug: en_US, $enLang[0], $enLang[1]\n";
}

// Let's process the language folders.
$definedLanguages = getDefinedLanguages($path);
foreach ($definedLanguages as $langCode) {

    // Redo the XML part thanks to a suggestion by Nicolas Ruflin.
    // Nicolas, thanks for the PHP lesson.
    $xml = simplexml_load_file("$path/$langCode/info.xml");

    $tmp = explode(',', $xml->author);
    $xml->author = join(', ', $tmp);
    if (SI_DEBUG) {
        echo "debug: $xml->name, $xml->author\n";
    }

    /*
     Process the language files
    */

    $count = processLangFile($path, $langCode);
    if (SI_DEBUG) {
        echo "debug: $langCode, $count[0], $count[1]\n";
    }

    if ($count[0] == 0) {
        $percentage = 'N/A';
    } else {
        // The percentage is compared with the total strings of the english language.
        $percentage = number_format(round($count[1] / $enLang[0] * 100, 2), 2) . '%';
    }

    // New strings available?
    if ($enLang[0] - $count[0] == 0) {
        $newStrings = 'All Done';
    } else {
        $newStrings = $enLang[0] - $count[0];
    }


    echo sprintf("%-10s", $langCode) . " | ";
    echo sprintf("%-29s", utf8_decode("$xml->name ($xml->englishname)")) . " | ";
    echo sprintf("%-11s", $newStrings) . " | ";
    echo sprintf("%15s", $count[0]) . " | ";
    echo sprintf("%16s", $count[1]) . " | ";
    echo sprintf("%8s", $percentage) . " | ";
    if (SI_AUTHOR) {
        echo sprintf("%10s", trim($xml->author)) . " | ";
    }
    echo "\n";
}

// Footer
echo str_repeat("-", $uLine);
echo "\n";
