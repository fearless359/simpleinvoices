<!DOCTYPE html>
<html lang="en">
<head>
    <title>SimpleInvoices - About</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="../../../templates/default/css/main.css">
    <link rel="stylesheet" href="../../../templates/default/css/info.css">
    <?php
    function printVersionInfo()
    {
        if (($lines = file("../../../config/config.php")) === false) {
            echo "<i style='color:red>Version info not available.</i>";
            return;
        }
        $fnd_section = false;
        $info_fnd_cnt = 0;
        $v_name = '';
        $v_date = '';
        foreach ($lines as $line) {
            $line = trim($line);
            // Search for pattern (sans quotes): "   [xA0_ -.]". Ex: "   [Section_A 1]"
            if (preg_match('/^ *\[[a-zA-Z0-9_ \-\.]+\]/', $line) === 1) {
                if ($fnd_section) break; // end of selected section
                $beg = strpos($line, '[') + 1;
                $len = strpos($line, ']') - $beg;
                $section = substr($line, $beg, $len);
                $fnd_section = ($section == "production");
            } else if ($fnd_section) {
                $parts = explode('=', $line);
                if (count($parts) == 2) {
                    if (trim($parts[0]) == "version.name") {
                        $v_name = trim($parts[1]);
                        $info_fnd_cnt++;
                    } else if (trim($parts[0]) == "version.update_date") {
                        $v_date = trim($parts[1]);
                        $info_fnd_cnt++;
                    }

                    if ($info_fnd_cnt == 2) {
                        echo 'Version: ' . $v_name . '  --  ' . $v_date;
                        return;
                    }
                }
            }
        }
        echo "<i style='color:red;'>Unable to access version information</i>";
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
    <p>Homepage: <a href='https://simpleinvoices.group'>https://simpleinvoices.group</a></p>
</div>
</body>
</html>
