<!DOCTYPE html>
<html lang="en">
<head>
    <title>SimpleInvoices - About</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="stylesheet" href="../../css/main.css">
    <?php
    function printVersionInfo()
    {
        if (($lines = file("../../config/config.ini")) === false) {
            echo "<em class='error'>Version info not available.</em>";
            return;
        }
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
                        echo 'Version: ' . $vName . '  --  ' . $vDate;
                        return;
                    }
                }
            }
        }
        echo "<em class='error'>Unable to access version information</em>";
    }

    ?>
</head>
<body>
<div class="container">
    <h1 class="align__text-center margin__top-0-75">About</h1>
    <div class="margin__top-0-75">
        <div class="align__text-center">
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                <button>Return To Previous Screen</button>
            </a>
        </div>
        <br/>
        <div class="align__text-center">
            <p><?php printVersionInfo(); ?><br/>
                Forum homepage: <a href='https://simpleinvoices.group' target="_blank">https://simpleinvoices.group</a></p>
        </div>
    </div>
</div>
</body>
</html>
