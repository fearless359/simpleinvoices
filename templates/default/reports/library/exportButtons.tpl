{if isset($smarty.post.submit) || (isset($showReportExportButtons) && $showReportExportButtons) ||
                                  (isset($displayExportButtonsNow) && $displayExportButtonsNow)}
    <div class="si_toolbar si_toolbar_top">
        <a title="{$LANG.printPreview}" target="_blank"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=print">
            <img src='{$path}../../../images/printer.png' class='action'  alt=""/>&nbsp;{$LANG.printPreview}
        </a>
        <a title="{$LANG.exportPdf}"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=pdf">
            <img src='{$path}../../../images/page_white_acrobat.png' class='action'  alt=""/>&nbsp;{$LANG.exportPdf}
        </a>
        <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet} {$LANG.formatTooltip}"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=file&amp;fileType={$config.exportSpreadsheet}">
            <img src='{$path}../../../images/page_white_excel.png' class='action'  alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportSpreadsheet}
        </a>
        <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor} {$LANG.formatTooltip}"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=file&amp;fileType={$config.exportWordProcessor}">
            <img src='{$path}../../../images/page_white_word.png' class='action'  alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportWordProcessor}
        </a>
        <a title="{$LANG.email}"
           href="index.php?module=reports&amp;view=email{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;stage=1&amp;format=file">
            <img src='{$path}../../../images/mail-message-new.png' class='action'  alt=""/>&nbsp;{$LANG.email}
        </a>
    </div>
{/if}
