<?php
$secure = true;
if($secure)
{
    exit("=========================<br/>" .
         "<strong style='color:red;'>SimpleInvoices security warning</strong><br/>" .
         "=========================<br/>" .
         "<br/>" .
         "<strong>si2020Converter.php</strong> is disabled by default for security reasons.<br/>" .
         "<br/>" .
         "This program is meant for a one time use when upgrading from <strong>master_2019.2</strong> to <strong>master_2020</strong>.<br/>" .
         "To use this program, change the <strong>\$secure</strong> variable setting from <strong>true</strong> to <strong>false</strong>.<br/>" .
         "After you run this program, you should change this setting back.");
} else {
    $cvtFromTo = [
        'authentication.enabled' => 'authenticationEnabled',
        'authentication.http' => 'authenticationHttp',
        'confirm.deleteLineItem' => 'confirmDeleteLineItem',
        'database.adapter' => 'databaseAdapter',
        'database.params.dbname' => 'databaseDbname',
        'database.params.host' => 'databaseHost',
        'database.params.password' => 'databasePassword',
        'database.params.port' => 'databasePort',
        'database.params.username' => 'databaseUsername',
        'database.utf8' => 'databaseUtf8',
        'debug.error_reporting' => 'debugErrorReporting',
        'debug.level' => 'debugLevel',
        'email.ack' => 'emailAck',
        'email.host' => 'emailHost',
        'email.password' => 'emailPassword',
        'email.secure' => 'emailSecure',
        'email.smtp_auth' => 'emailSmtpAuth',
        'email.smtpport' => 'emailSmtpPort',
        'email.use_local_sendmail' => 'emailUseLocalSendmail',
        'email.username' => 'emailUsername',
        'encryption.default.key' => 'encryptionDefaultKey',
        'export.pdf.bottommargin' => 'exportPdfBottomMargin',
        'export.pdf.defaultfontsize' => 'exportPdfDefaultFontSize',
        'export.pdf.leftmargin' => 'exportPdfLeftMargin',
        'export.pdf.papersize' => 'exportPdfPaperSize',
        'export.pdf.rightmargin' => 'exportPdfRightMargin',
        'export.pdf.topmargin' => 'exportPdfTopMargin',
        'export.spreadsheet' => 'exportSpreadsheet',
        'export.wordprocessor' => 'exportWordProcessor',
        'local.currency_code' => 'localCurrencyCode',
        'local.locale' => 'localLocale',
        'local.precision' => 'localPrecision',
        'nonce.key' => 'nonceKey',
        'nonce.timelimit' => 'nonceTimeLimit',
        'phpSettings.date.timezone' => 'phpSettingsDateTimezone',
        'phpSettings.display_errors' => 'phpSettingsDisplayErrors',
        'phpSettings.display_startup_errors' => 'phpSettingsDisplayStartupErrors',
        'phpSettings.error_log' => 'phpSettingsErrorLog',
        'phpSettings.log_errors' => 'phpSettingsLogErrors',
        'version.name' => 'versionName',
        'version.update_date' => 'versionUpdateDate',
        'zend.logger_level' => 'loggerLevel'
    ];

    $dbgCmtCvt = [
        'DEBUG\(7\)' => 'DEBUG(100)',
        'INFO\(6\)' => 'INFO(200)',
        'NOTICE\(5\)' => 'NOTICE(250)',
        'WARN\(4\)' => 'WARNING(300)',
        'ERR\(3\)' => 'ERROR(400)',
        'CRIT\(2\)' => 'CRITICAL(500)',
        'ALERT\(1\)' => 'ALERT(550)',
        'EMERG\(0\)' => 'EMERGENCY(600)'
    ];

    $cfgIn = 'config/custom.config.php';
    if (!file_exists($cfgIn)) {
        exit("no file ({$cfgIn}) exists to convert");
    }

    $cfgOut = 'config/custom.config.ini';
    if (file_exists($cfgOut)) {
        exit("file ({$cfgOut}) exists. Must remove it if you want to convert it again.");
    }

    $lines = file($cfgIn);
    $newLines = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            // Handel section headings
            $newLines[] = '';
        } elseif (preg_match('/^\[.*\]/', $line)) {
            $newLines[] = $line;
        } elseif (preg_match('/^;.*$/', $line)) {
            // Comment lines. Convert debug comment values if on this line.
            foreach ($dbgCmtCvt as $key => $val) {
                $pattern = "/^(.*){$key}(.*)$/";
                $line = preg_replace($pattern, "$1{$val}$2", $line);
            }
            $newLines[] = $line;
        } else {
            // Command lines
            $parts = explode('=', $line);
            $key = trim($parts[0]);
            if (!empty($cvtFromTo[$key]) && count($parts) >= 2) {
                $pattern = "/^'(.*)'.*$/";
                $val = preg_replace($pattern, '$1', trim($parts[1]));
                if ($cvtFromTo[$key] == 'loggerLevel') {
                    if ($val == "ERR") {
                        $val = "ERROR";
                    } elseif ($val == "CRIT") {
                        $val = "CRITICAL";
                    } elseif ($val == "EMERG") {
                        $val = "EMERGENCY";
                    }
                }
                $newLine = $cvtFromTo[$key] . ' = ' . $val;
                $newLines[] = $newLine;
            } else {
                $newLines[] = '; **** The following line was not converted and must me manually handled';
                $newLines[] = $line;
                $newLines[] = '; **********************************************************************';
            }
        }
    }

    $fp = fopen($cfgOut, 'w');
    foreach ($newLines as $newLine) {
        $newLine = preg_replace('/^(.*)\R/', '$1', $newLine);
        if (fwrite($fp, $newLine . "\n") === false) {
            echo "<strong style='color:red;'>Unable to output line[{$newLine}]";
            exit("Conversion Failed");
        }
    }
    fclose($fp);

    echo "<strong style='color:green;'>{$cfgOut} file has been successfully created from {$cfgIn}";
    exit();
}
