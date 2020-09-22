<div id="top"><h3>{$LANG.monthlyUc} {$LANG.tax} {$LANG.summaryUc} {$LANG.per} {$LANG.yearUc}</h3></div>

<table class="center" style="width:100%">
    {foreach $years as $year}
        <tr>
            <td class="details_screen"><b>{if isset($year)}{$year}{/if}</b></td>
        <tr>
            <td></td>
            <td class="details_screen">
                <b>{$LANG.monthUc}:
                <br/>{$LANG.tax} {$LANG.onLc} {$LANG.invoices}:
                <br/>{$LANG.tax} {$LANG.onLc} {$LANG.expenses}:
                <br/>{$LANG.tax} {$LANG.owing}:</b>
            </td>
            {foreach $total_sales.$year as $key => $item_sales}
                <td class="details_screen si_right">
                    <b>{if isset($key)}{$key}{/if}</b>
                    <br/>{if empty($item_sales|utilNumberTrim)}{$item_sales|utilNumberTrim}{else}{$item_sales|utilCurrency}{/if}
                    <br/>{if empty($total_payments.$year.$key|utilNumberTrim)}{$total_payments.$year.$key|utilNumberTrim}{else}{$total_payments.$year.$key|utilCurrency}{/if}
                    <br/>{if empty($tax_summary.$year.$key|utilNumberTrim)}{$tax_summary.$year.$key|utilNumberTrim}{else}{$tax_summary.$year.$key|utilCurrency}{/if}
                </td>
            {/foreach}
        </tr>
    {/foreach}
</table>
