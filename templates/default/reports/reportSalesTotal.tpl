{include file="templates/default/reports/library/reportTitle.tpl" title=$title}
{include file="templates/default/reports/library/exportButtons.tpl"
         params=[
             'endDate' => $endDate|urlencode,
             'fileName' => "reportSalesTotal",
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportSalesTotal">
        <table class="center">
            {include file="templates/default/reports/library/dateRangePrompt.tpl"}
        </table>
        <br/>
        {include file="templates/default/reports/library/runReportButton.tpl" value="reportSalesTotal" label=$LANG.runReport}
        <br/>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file="templates/default/reports/reportSalesTotalBody.tpl"}
{/if}
