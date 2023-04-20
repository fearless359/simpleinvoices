{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'customFlag' => $customFlag|urlEncode,
             'customFlagLabel' => $customFlagLabel|urlEncode,
             'customerId' => $customerId|urlEncode,
             'displayDetail' => $displayDetail|urlEncode,
             'endDate' => $endDate|urlEncode,
             'fileName' => "reportNetIncome",
             'startDate' => $startDate|urlEncode,
             'title' => $title|urlEncode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportNetIncome">
        <div class="si_form si_form_search">
            <div class="grid__area">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
                {include file=$path|cat:"library/customerSelectList.tpl"}
                {include file=$path|cat:"library/customFlagSelectList.tpl"}
                {include file=$path|cat:"library/displayDetail.tpl"}
                {include file=$path|cat:"library/runReportButton.tpl" value="netIncomeReport" label=$LANG.runReport}
            </div>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file="templates/default/reports/reportNetIncomeBody.tpl"}
{/if}
