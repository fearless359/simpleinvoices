{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'fileName' => "reportSalesByPeriods",
             'showRates' => "{$showRates}",
             'title' => $title|urlEncode
        ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportSalesByPeriods&amp;showAllReports={$showAllReports}">
        <div class="si_form si_form_search">
            <div class="grid__area">
                {include file=$path|cat:"library/showRates.tpl"}
                {include file=$path|cat:"library/runReportButton.tpl" value="salesByPeriods" label=$LANG.runReport}
            </div>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportSalesByPeriodsBody.tpl"}
{/if}
