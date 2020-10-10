{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'endDate' => $endDate|urlencode,
             'fileName' => "reportInvoiceProfit",
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportInvoiceProfit">
        <table class="center">
            {include file=$path|cat:"library/dateRangePrompt.tpl"}
        </table>
        <br/>
        {include file=$path|cat:"library/runReportButton.tpl" value="reportInvoiceProfit" label=$LANG.runReport}
        <br/>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportInvoiceProfitBody.tpl"}
{/if}
