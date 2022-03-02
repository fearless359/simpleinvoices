<?php
global $LANG, $menu, $smarty;

$menu = true;

if (($lines = file("config/config.ini")) === false) {
    $aboutInfo = "<em class='error'>{$LANG['versionInfoNotAvailable']}</em>";
} else {
    // Default if no version information found
    $aboutInfo = "<em class='error'>{$LANG['unableToAccessVersionInformation']}</em>";
    $fndSection = false;
    $infoFndCnt = 0;
    $vName = '';
    $vDate = '';
    foreach ($lines as $line) {
        $line = trim($line);
        // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
        /** @noinspection RegExpRedundantEscape */
        $pattern = '/^ *\[[a-zA-Z0-9_: \-\.]+\]/';
        if (preg_match($pattern, $line) === 1) {
            if ($fndSection) {
                break; // end of selected section
            }
            $beg = strpos($line, '[') + 1;
            $len = strpos($line, ']') - $beg;
            $section = substr($line, $beg, $len);
            $fndSection = $section == "production";
        } elseif ($fndSection) {
            $parts = explode('=', $line);
            if (count($parts) == 2) {
                if (trim($parts[0]) == "versionName") {
                    $vName = trim($parts[1]);
                    $infoFndCnt++;
                } elseif (trim($parts[0]) == "versionUpdateDate") {
                    $vDate = trim($parts[1]);
                    $infoFndCnt++;
                }

                if ($infoFndCnt == 2) {
                    $aboutInfo = "Version: $vName -- $vDate";
                    break;
                }
            }
        }
    }
}

$smarty->assign('aboutInfo', $aboutInfo);
