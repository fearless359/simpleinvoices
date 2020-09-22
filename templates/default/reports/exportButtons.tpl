{if isset($smarty.post.submit)}
    <div class="si_toolbar si_toolbar_top">
        <a title="{$LANG.printPreview}"
           href="index.php?module=reports&amp;view=export&amp;reportName=reportNetIncome&amp;startDate={$params.startDate}&amp;endDate={$params.endDate}&amp;customerId={$params.customerId}&amp;excludeCustomFlag={$params.excludeCustomFlag}&amp;displayDetail={$displayDetail}&amp;format=print">
            <img src='../../../images/printer.png' class='action'  alt=""/>&nbsp;{$LANG.printPreview}
        </a>
{*        <a title="{$LANG.exportPdf}"*}
{*           href="index.php?module=reports&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=pdf">*}
{*            <img src='../../../images/page_white_acrobat.png' class='action'  alt=""/>&nbsp;{$LANG.exportPdf}*}
{*        </a>*}
{*        <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet} {$LANG.formatTooltip}"*}
{*           href="index.php?module=reports&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file&amp;filetype={$config.exportSpreadsheet}">*}
{*            <img src='../../../images/page_white_excel.png' class='action'  alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportSpreadsheet}*}
{*        </a>*}
{*        <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor} {$LANG.formatTooltip}"*}
{*           href="index.php?module=reports&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file&amp;filetype={$config.exportWordProcessor}">*}
{*            <img src='../../../images/page_white_word.png' class='action'  alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportWordProcessor}*}
{*        </a>*}
{*        <a title="{$LANG.email}"*}
{*           href="index.php?module=reports&amp;view=email&amp;stage=1&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file">*}
{*            <img src='../../../images/mail-message-new.png' class='action'  alt=""/>&nbsp;{$LANG.email}*}
{*        </a>*}
    </div>
{/if}
