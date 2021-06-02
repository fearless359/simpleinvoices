{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'customFlag' => $customFlag|urlencode,
             'customFlagLabel' => $customFlagLabel|urlencode,
             'customerId' => $customerId|urlencode,
             'displayDetail' => $displayDetail|urlencode,
             'endDate' => $endDate|urlencode,
             'fileName' => "reportNetIncome",
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportNetIncome">
        <div class="si_form si_form_search">
            <table class="center">
                {include file="templates/default/reports/library/dateRangePrompt.tpl"}
                {include file="templates/default/reports/library/customerSelectList.tpl"}
                <tr>
                    <td class="si_right nowrap" style="padding-right: 10px; width: 47%;">
                        <label for="customFlagId">{$LANG.excludeUc} {$LANG.customFlagUc} #:</label>
                    </td>
                    <td>
                        <select name="customFlag" id="customFlagId">
                            <option value="0" {if $customFlag == 0} selected {/if}>{$LANG.none}</option>
                            {foreach $customFlagLabels as $ndx => $label}
                                {if $label != ''}
                                    <option value="{$ndx+1}" {if $customFlag - 1 == $ndx} selected {/if}>{$ndx+1}&nbsp;-&nbsp;{$label}</option>
                                {/if}
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {include file="templates/default/reports/library/displayDetail.tpl"}
            </table>
            <br/>
            {include file="templates/default/reports/library/runReportButton.tpl" value="netIncomeReport" label=$LANG.runReport}
            <br/>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file="templates/default/reports/reportNetIncomeBody.tpl"}
{/if}
