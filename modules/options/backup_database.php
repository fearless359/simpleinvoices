<?php

use Inc\Claz\BackupDb;
use Inc\Claz\PdoDbException;

global $help_image_path, $smarty, $LANG;

$smarty->assign('pageActive', 'backup');
$smarty->assign('active_tab', '#setting');

if (isset($_GET['op']) && $_GET['op'] == "backup_db") {
    $today = date("YmdGisa");
    $ok = true;
    try {
        $oBack = new BackupDb();
        $filename = "tmp/database_backups/simple_invoices_backup_$today.sql"; // output file name
        $oBack->start_backup($filename);
    } catch (PdoDbException $pde) {
        error_log("modules/options/backup_database.php - error: " . $pde->getMessage());
        $ok = false;
    }
    
    if ($ok) {
        $txt = sprintf($LANG['backup_done'], $filename);

        $display_block =
            "<div class='si_center'>" .
                "<pre>" .
                    "<table>{" . $oBack->getOutput() . "}</table>" .
              "</pre>" .
            "</div>" .
            $txt .
            "<div class='si_help_div'>" .
                "<a class='cluetip' href='#' title='{$LANG['fwrite_error']}'" .
                   "rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite'>" .
                    "<img src='{$help_image_path}help-small.png' alt=''/>" .
                    "{$LANG['fwrite_error']}" .
                "</a>" .
            "</div>";
    } else {
        $display_block =
            "<div class='si_message_error'>ERROR: Unable to complete the backup. See error log for details.</div>" .
            "<div class='si_help_div'>" .
                "<a class='cluetip' href='#' title='{$LANG['fwrite_error']}'" .
                   "rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite'>" .
                    "<img src='{$help_image_path}help-small.png' alt=''/>" .
                    "{$LANG['failure']}" .
                "</a>" .
            "</div>";
    }
} else {
    $display_block =
        "<div class='si_center'>" .
            "{$LANG['backup_howto']}" .
            "<div class='si_toolbar si_toolbar_top'><br/>" .
                "<a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'>" .
        "<img src='../../images/database_save.png' alt=''/>" .
                    "{$LANG['backup_database_now']}" .
                "</a>" .
            "</div>" .
            "{$LANG['note']}: {$LANG['backup_note_to_file']}" .
        "</div>" .
        "<div class='si_help_div'>" .
            "<a class='cluetip' href='#' title='{$LANG['database_backup']}'" .
               "rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database' >" .
        "<img src='../../images/important.png' alt='' />" .
                "{$LANG['more_info']}" .
            "</a>" .
        "</div>";
}

$oBack = null;
$smarty->assign('display_block', $display_block);
