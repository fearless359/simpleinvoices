<?php
/* **************
//    Purpose: To insert untranslated strings into language file
//             and arrange the non title_* keys in alphabetical order
//    Author : Ap.Muthu
//    Release: 2013-10-18
//    Updated: 2013-10-19
//
//    Usage  : http://si_domain.com/lang/langInsert.php?l=vi_VN
//             The Source HTML would be the raw text for the lang.php file
*/
global $argv, $LANG;

define('RUNNING_IN_BASH_SHELL', true);

include_once "langFunctions.php";
$definedLanguages = getDefinedLanguages();

// Ensure that the lang folder name is of correct format and get it
$langCmp = '';
if (!empty($_REQUEST['1'])) {
    $langCmp = $_REQUEST['1'];
} elseif (!empty($argv[1])) {
    $langCmp = trim($argv[1]);
}
if (!preg_match('/^[a-z]{2}_[a-z]{2}$/i', $langCmp)) {
    die("Language file to compare to not defined.");
}

// Ensure that the requested lang folder exists
if (!in_array($langCmp, $definedLanguages, true)) {
    exit("Invalid Language. - langCmp[$langCmp] definedLanguages");
}

include "en_US/lang.php";
$langEn = $LANG;
/* *********************************************
 * Commented out by RCR as was in conflict with
 * unset($LANG[$uKey]) function below.
 * *********************************************/
//unset($LANG);

$preamble = '';
$nl = chr(10);

include "{$langCmp}/lang.php";

$fh = fopen("$langCmp/lang.php", "r");
while ($line = fgets($fh)) {
    if (substr($line, 0, 6) != '$LANG[' && substr($line, 0, 2) !='?>') {
        $preamble .= $line;
    } elseif (  !preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)
              && preg_match('/^\$LANG\[.*;\s*\/\/\s*0/', $line)) {
        // Untranslated strings in lang file
        $uKeyArr = explode("'", $line, 3);
        $uKey = $uKeyArr[1];
        unset($uKeyArr);
        unset($LANG[$uKey]);
    }
}

foreach ($langEn AS $key => $val) {
    if (! isset($LANG[$key])) {
        // Untranslated String
        $LANG[$key][0] = $val;
        $LANG[$key][1] = 0;
    } else {
        // Translated String
        $val = $LANG[$key];
        unset($LANG[$key]);
        $LANG[$key][0] = $val;
        $LANG[$key][1] = 1;
    }
}

$langGen = [];
$langTitle = [];
foreach ($LANG as $key => $val) {
    $baseStr = '$LANG[' . "'" . $key ."'" .  '] = "' . $val[0] . '";//' . $val[1];
    if (substr($key, 0, 5) == 'title') {
        $langTitle[$key] = $baseStr;
    } else {
        $langGen[$key] = $baseStr;
    }
}

ksort($langGen);

echo $preamble;

foreach ($langGen as $val) {
    echo $val . $nl;
}
echo $nl;
foreach ($langTitle as $val) {
    echo $val . $nl;
}

echo $nl;
echo '?>';
echo $nl;
