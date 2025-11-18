{if isset($smarty.post.submit) || (isset($showReportExportButtons) && $showReportExportButtons) ||
                                  (isset($displayExportButtonsNow) && $displayExportButtonsNow)}
    <div class="flex__container">
        <a title=" {$LANG.printUc}" target="_blank" class="button square"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=print">
            <img src='{$path}../../../images/printer.png' class='action desktopOnly' alt=""/>&nbsp; {$LANG.printUc}
        </a>
        <a title="{$LANG.exportPdf}" class="button square"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=pdf">
            <img src='{$path}../../../images/page_white_acrobat.png' class='action desktopOnly' alt=""/>&nbsp;{$LANG.exportPdf}
        </a>
        <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet} {$LANG.formatTooltip}" class="button square"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=file&amp;fileType={$config.exportSpreadsheet}">
            <img src='{$path}../../../images/page_white_excel.png' class='action desktopOnly'
                 alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportSpreadsheet}
        </a>
        <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor} {$LANG.formatTooltip}" class="button square"
           href="index.php?module=reports&amp;view=export{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=file&amp;fileType={$config.exportWordProcessor}">
            <img src='{$path}../../../images/page_white_word.png' class='action desktopOnly'
                 alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportWordProcessor}
        </a>
        <a title="{$LANG.email}" class="button square"
           href="index.php?module=reports&amp;view=email{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;stage=1&amp;format=file">
            <img src='{$path}../../../images/mail-message-new.png' class='action desktopOnly' alt=""/>&nbsp;{$LANG.email}
        </a>
    </div>
{/if}
