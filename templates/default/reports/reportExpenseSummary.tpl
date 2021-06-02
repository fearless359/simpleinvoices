{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
params=[
'endDate' => $endDate|urlencode,
'fileName' => "reportExpenseSummary",
'startDate' => $startDate|urlencode,
'title' => $title|urlencode
]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportExpenseSummary">
        <div class="si_form si_form_search">
            <table class="center">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
            </table>
            <br/>
            {include file=$path|cat:"library/runReportButton.tpl" value="reportExpenseSummary" label=$LANG.runReport}
            <br/>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportExpenseSummaryBody.tpl"}
{/if}
