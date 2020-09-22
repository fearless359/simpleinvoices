{include file="templates/default/reports/reportTitle.tpl" title=$LANG.netIncomeReport}
{include file="templates/default/reports/exportButtons.tpl"
         params=[
            'startDate' => $startDate|urlencode,
            'endDate' => $endDate|urlencode,
            'customerId' => $customerId|urlencode,
            'excludeCustomFlag' => $customFlag.value|urlencode,
            'displayDetail' => $displayDetail|urlencode
         ]
}

{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportNetIncome">
        <table class="center">
            {include file="templates/default/reports/dateRangePrompt.tpl"}
            {include file="templates/default/reports/customerSelectList.tpl"}
            <tr>
                <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    <label for="customFlagId">{$LANG['exclude']} {$LANG.customFlagUc} #:</label>
                </td>
                <td>
                    <select name="customFlag" id="customFlagId">
                        <option value="0" {if $customFlag == 0} selected {/if}>{$LANG['none']}</option>
                        {foreach $customFlagLabels as $ndx => $label}
                            {if $label != ''}
                                <option value="{$ndx+1}" {if $customFlag - 1 == $ndx} selected {/if}>{$ndx+1}&nbsp;-&nbsp;{$label}</option>
                            {/if}
                        {/foreach}
                    </select>
                </td>
            </tr>
            {include file="templates/default/reports/displayDetail.tpl"}
        </table>
        <br/>
        {include file="templates/default/reports/runReportButton.tpl" value="statement_report" label=$LANG.runReport}
        <br/>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == export}
    {include file="templates/default/reports/reportNetIncomeBody.tpl"}
{/if}
