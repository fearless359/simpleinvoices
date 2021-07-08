{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'billerId' => $billerId|urlencode,
             'customerId' => $customerId|urlencode,
             'endDate' => $endDate|urlencode,
             'fileName' => "reportStatements",
             'filterByDateRange' => $filterByDateRange|urlencode,
             'includePaidInvoices' => $includePaidInvoices|urlencode,
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
        ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=reports&amp;view=reportStatement">
        <div class="si_form si_form_search">
            <div class="grid__area">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
                {include file=$path|cat:"library/billerSelectList.tpl"}
                {include file=$path|cat:"library/customerSelectList.tpl"}
                {include file=$path|cat:"library/filterByDateRange.tpl"}
                {include file=$path|cat:"library/includePaidInvoices.tpl"}
                {include file=$path|cat:"library/runReportButton.tpl" value="statement_report" label=$LANG.runReport}
            </div>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file="templates/default/reports/reportStatementsBody.tpl"}
{/if}
