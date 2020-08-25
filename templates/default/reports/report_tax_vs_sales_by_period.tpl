<div id="top"><h3>{$LANG.monthly_uc} {$LANG.tax} {$LANG.summary_uc} {$LANG.per} {$LANG.year_uc}</h3></div>

<table style="width:100%;">
    {foreach item=year from=$years}
        <tr>
            <td class="details_screen"><b>{if isset($year)}{$year}{/if}</b></td>
        <tr>
            <td></td>
            <td class="details_screen">
                <b>{$LANG.month_uc}:
                {* The syntax to $LANG['on'] is required to get around editor flagging as an error. *}
                <br/>{$LANG.tax} {$LANG['on']} {$LANG.invoices}:
                <br/>{$LANG.tax} {$LANG['on']} {$LANG.expenses}:
                <br/>{$LANG.tax} {$LANG.owing}:</b>
            </td>
            {foreach key=key item=item_sales from=$total_sales.$year}
                <td class="details_screen">
                    <b>{if isset($key)}{$key}{/if}</b>
                    <br/>{if isset($item_sales|siLocal_number_trim)}{$item_sales|siLocal_number_trim}{/if}&nbsp;
                    <br/>{$total_payments.$year.$key|siLocal_number_trim}&nbsp;
                    <br/>{$tax_summary.$year.$key|siLocal_number_trim}&nbsp;
                </td>
            {/foreach}
        </tr>
    {/foreach}
</table>
