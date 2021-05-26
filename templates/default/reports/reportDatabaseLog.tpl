{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'endDate' => $endDate|urlencode,
             'fileName' => "reportDatabaseLog",
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportDatabaseLog">
        <div class="si_form si_form_search">
            <table class="center">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
            </table>
            <br/>
            {include file=$path|cat:"library/runReportButton.tpl" value="reportDatabaseLog" label=$LANG.runReport}
            <br/>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportDatabaseLogBody.tpl"}
{/if}
