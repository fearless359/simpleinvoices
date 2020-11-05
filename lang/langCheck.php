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
 *      php -q langCheck.php > langCheck.txt
 *
 */


/*
 * Get the language codes ('en', 'pt', etc) that exists in this folder.
 */

define("SI_DEBUG", false);
define("SI_AUTHOR", false);
$uLine = 106;
if (SI_AUTHOR) {
    $uLine += 11;
}

include_once "langFunctions.php";

// Header
print str_repeat("=", $uLine);
print "\n";
print sprintf("%-10s", 'Lang. Code') . " | ";
print sprintf("%-29s", 'Lang. name') . " | ";
print sprintf("%-11s", 'New strings') . " | ";
print sprintf("%15s", 'Strings in file') . " | ";
print sprintf("%16s", 'Total translated') . " | ";
print sprintf("%8s", '% Done') . " | ";
if (SI_AUTHOR) {
    print sprintf("%10s", 'Authors');
}
print "\n";
print str_repeat("=", $uLine);
print "\n";

// The main language. Needed to compare the % done of the other languages.
$enLang = processLangFile('en_US');
if (SI_DEBUG) {
    echo "debug: en_US, {$enLang[0]}, {$enLang[1]}\n";
}

// Lets process the language folders.
foreach (getDefinedLanguages() as $langCode) {

    // Redo the XML part thanks to a suggestion by Nicolas Ruflin.
    // Nicolas, thanks for the PHP lesson.
    $xml = simplexml_load_file("{$langCode}/info.xml");

    $tmp = explode(',', $xml->author);
    $xml->author = join(', ', $tmp);
    if (SI_DEBUG) {
        echo "debug: {$xml->name}, {$xml->author}\n";
    }

    /*
     Process the language files
    */

    $count = processLangFile($langCode);
    if (SI_DEBUG) {
        echo "debug: {$langCode}, {$count[0]}, {$count[1]}\n";
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


    print sprintf("%-10s", $langCode) . " | ";
    print sprintf("%-29s", utf8_decode("{$xml->name} ({$xml->englishname})")) . " | ";
    print sprintf("%-11s", $newStrings) . " | ";
    print sprintf("%15s", $count[0]) . " | ";
    print sprintf("%16s", $count[1]) . " | ";
    print sprintf("%8s", $percentage) . " | ";
    if (SI_AUTHOR) {
        print sprintf("%10s", trim($xml->author)) . " | ";
    }
    print "\n";
}

// Footer
print str_repeat("-", $uLine);
print "\n";

