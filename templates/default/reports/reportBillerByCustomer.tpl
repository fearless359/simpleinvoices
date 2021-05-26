{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'billerId' => $billerId|urlencode,
             'endDate' => $endDate|urlencode,
             'fileName' => "reportBillerByCustomer",
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportBillerByCustomer">
        <div class="si_form si_form_search">
            <table class="center">
                {include file=$path|cat:"library/dateRangePrompt.tpl"}
                {include file=$path|cat:"library/billerSelectList.tpl"}
            </table>
            <br/>
            {include file=$path|cat:"library/runReportButton.tpl" value="reportBillerByCustomer" label=$LANG.runReport}
            <br/>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportBillerByCustomerBody.tpl"}
{/if}
