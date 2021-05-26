<!DOCTYPE html>
<html lang="en">
<head>
    <title>SimpleInvoices - About</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../templates/default/css/info.css">
    <?php
    function printVersionInfo()
    {
        if (($lines = file("../../config/config.ini")) === false) {
            echo "<i style='color: red;'>Version info not available.</i>";
            return;
        }
        $fndSection = false;
        $infoFndCnt = 0;
        $vName = '';
        $vDate = '';
        foreach ($lines as $line) {
            $line = trim($line);
            // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
            $pattern = '/^ *\[[a-zA-Z0-9_ \-\.]+\]/';
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
        echo "<i style='color: red;'>Unable to access version information</i>";
    }

    ?>
</head>
<body>
<h1 class="si_center">About</h1>
<div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous Screen</a>
</div>
<br/>
<br/>
<div class="si_center">
    <p><?php printVersionInfo();?></p>
    <p>Forum homepage: <a href='https://simpleinvoices.group' target="_blank">https://simpleinvoices.group</a></p>
</div>
</body>
</html>
