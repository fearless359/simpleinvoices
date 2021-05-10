<?php

use Inc\Claz\BackupDb;
use Inc\Claz\PdoDbException;

global $helpImagePath, $pdoDb, $smarty, $LANG;

$smarty->assign('pageActive', 'backup');
$smarty->assign('activeTab', '#settings');

if (isset($_GET['op']) && $_GET['op'] == "backup_db") {
    $today = date("YmdGisa");
    $ok = true;
    $oBack = null;
    $filename = "tmp/database_backups/simple_invoices_backup_$today.sql"; // output file name
    try {
        $oBack = new BackupDb();
        $oBack->startBackup($filename, $pdoDb, $LANG);
    } catch (PdoDbException $pde) {
        error_log("modules/options/backup_database.php - error: " . $pde->getMessage());
        $ok = false;
    }
    
    if ($ok) {
        $txt = sprintf($LANG['backupDone'], $filename);

        $displayBlock =
            "<div class='si_center'>" .
                "<pre>" .
                    "<table>{" . $oBack->getOutput() . "}</table>" .
              "</pre>" .
            "</div>" .
            $txt .
            "<div class='si_help_div'>" .
                "<a class='cluetip' href='#' title='{$LANG['fwriteError']}' " .
                   "rel='index.php?module=documentation&amp;view=view&amp;page=helpBackupDatabaseFwrite'>" .
                    "<img src='{$helpImagePath}help-small.png' alt=''/>" .
                    "{$LANG['fwriteError']}" .
                "</a>" .
            "</div>";
    } else {
        $displayBlock =
            "<div class='si_message_error'>ERROR: Unable to complete the backup. See error log for details.</div>" .
            "<div class='si_help_div'>" .
                "<a class='cluetip' href='#' title='{$LANG['fwriteError']}' " .
                   "rel='index.php?module=documentation&amp;view=view&amp;page=helpBackupDatabaseFwrite'>" .
                    "<img src='{$helpImagePath}help-small.png' alt=''/>" .
                    "{$LANG['failure']}" .
                "</a>" .
            "</div>";
    }
} else {
    $displayBlock =
        "<div class='si_center'>" .
            "{$LANG['backupHowTo']}" .
            "<div class='si_toolbar si_toolbar_top'><br/>" .
                "<a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'>" .
        "<img src='../../images/database_save.png' alt=''/>" .
                    "{$LANG['backupDatabaseNow']}" .
                "</a>" .
            "</div>" .
            "{$LANG['note']}: {$LANG['backupNoteToFile']}" .
        "</div>" .
        "<div class='si_help_div'>" .
            "<a class='cluetip' href='#' title='{$LANG['databaseBackup']}' " .
               "rel='index.php?module=documentation&amp;view=view&amp;page=helpBackupDatabase' >" .
        "<img src='../../images/important.png' alt='' />" .
                "{$LANG['moreInfo']}" .
            "</a>" .
        "</div>";
}

$oBack = null;
$smarty->assign('display_block', $displayBlock);
