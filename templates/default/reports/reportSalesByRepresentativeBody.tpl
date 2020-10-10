{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../include/jquery/css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{if $filterByDateRange == "yes"}
    {include file=$path|cat:"library/dateRangeDisplay.tpl"}
{/if}
<br/>
<div style="display:inline;">
    <div class="si_center">
        <strong>{$LANG.salesRepresentative}:</strong> {if empty($salesRep)}{$LANG.allUc}{else}{$salesRep}{/if}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>{$LANG.salesSummary} {$LANG.totalUc}:</strong> {$statement.total|utilCurrency}
    </div>
</div>
<br/>
<table class="si_report_table">
    <thead>
        <tr>
            <th class="details_screen si_right">{$LANG.idUc}</th>
            <th class="details_screen si_center">{$LANG.dateUc}</th>
            <th class="details_screen si_center">{$LANG.billerUc}</th>
            <th class="details_screen si_center">{$LANG.customerUc}</th>
            <th class="details_screen si_right">{$LANG.totalUc}</th>
        </tr>
    </thead>
    <tbody>
        {foreach $invoices as $invoice}
            {if $invoice@index > 0 && $invoice.preference != $invoices[$invoice@index - 1].preference}
                <tr class="tr_{cycle values="A,B"}">
                    <td colspan="5"><br/></td>
                </tr>
            {/if}
            <tr class="tr_{cycle values="A,B"}">
                <td class="si_right">
                    {if ($view == "reportSalesByRepresentative")}
                        <a class="index_table" title="View Invoice {$invoice.index_id}"
                           href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id}">{$invoice.index_id}</a>
                    {else}
                        {$invoice.index_id}
                    {/if}
                </td>
                <td class="si_center">{$invoice.date|utilDate}</td>
                <td>{$invoice.biller}</td>
                <td>{$invoice.customer}</td>
                <td class="si_right {if $invoice@last}underline{/if}">{$invoice.total|utilCurrency}</td>
            </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="si_right bold" colspan="4">{$LANG.totalUc}:</td>
            <td class="si_right bold">{$statement.total|utilCurrency}</td>
        </tr>
    </tfoot>
</table>
