{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'endDate' => $endDate|urlEncode,
             'fileName' => "reportInvoiceProfit",
             'startDate' => $startDate|urlEncode,
             'title' => $title|urlEncode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportInvoiceProfit&amp;showAllReports={$showAllReports}">
        <div class="si_form si_form_search">
            <div class="grid__area">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
                {include file=$path|cat:"library/runReportButton.tpl" value="reportInvoiceProfit" label=$LANG.runReport}
            </div>>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportInvoiceProfitBody.tpl"}
{/if}
