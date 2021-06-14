{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h2 class="si_center">{$title}</h2>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <th>{$LANG.invoiceUc}&#35;</th>
        <th colspan="3">{$LANG.billerUc}</th>
        <th colspan="3">{$LANG.customerUc}</th>
        <th class="si_right">{$LANG.totalUc}</th>
        <th class="si_right">{$LANG.costUc}</th>
        <th class="si_right">{$LANG.profitUc}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $invoices as $invoice}
        {if $invoice@index > 0}
            {if $invoice.preference != $prevPreference}
                <
                <tr class="tr_{cycle values="A,B"}">
                    <td>&nbsp;</td>
                </tr>
            {/if}
        {/if}
        {assign 'prevPreference' $invoice.preference}
        <tr class="tr_{cycle values="A,B"}">
            <td class="si_right">
                {if $format != 'print' && $format != 'pdf' && $fileType != 'xls' && $fileType != 'doc'}
                    <a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoices[invoice].id|urlencode}">
                        {$invoice.index_id|htmlSafe}
                    </a>
                {else}
                    {$invoice.index_id|htmlSafe}
                {/if}
            </td>
            <td colspan="3">{$invoice.biller|htmlSafe}</td>
            <td colspan="3">{$invoice.customer|htmlSafe}</td>
            <td class="si_right {if $invoice@last}underline{/if}">{$invoice.total|utilCurrency}</td>
            <td class="si_right {if $invoice@last}underline{/if}">{$invoice.cost|utilCurrency}</td>
            <td class="si_right {if $invoice@last}underline{/if}">{$invoice.profit|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td class="si_right bold" colspan="7">{$LANG.totalUc}:</td>
        <td class="si_right bold">{$invoiceTotals.sumTotal|utilCurrency}</td>
        <td class="si_right bold">{$invoiceTotals.sumCost|utilCurrency}</td>
        <td class="si_right bold">{$invoiceTotals.sumProfit|utilCurrency}</td>
    </tr>
    </tfoot>
</table>
