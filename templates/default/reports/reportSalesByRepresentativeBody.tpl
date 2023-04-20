{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{if $filterByDateRange == "yes"}
    {include file=$path|cat:"library/dateRangeDisplay.tpl"}
{/if}
<br/>
<div style="display:inline;">
    <div class="align__text-center">
        <strong>{$LANG.salesRepresentative}:</strong> {if empty($salesRep)}{$LANG.allUc}{else}{$salesRep}{/if}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>{$LANG.salesSummary} {$LANG.totalUc}:</strong> {$statement.total|utilCurrency}
    </div>
</div>
<br/>
<table class="si_report_table">
    <thead>
        <tr>
            <th class="details_screen align__text-right">{$LANG.idUc}</th>
            <th class="details_screen align__text-center">{$LANG.dateUc}</th>
            <th class="details_screen align__text-center">{$LANG.billerUc}</th>
            <th class="details_screen align__text-center">{$LANG.customerUc}</th>
            <th class="details_screen align__text-right">{$LANG.totalUc}</th>
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
                <td class="align__text-right">
                    {if ($view == "reportSalesByRepresentative")}
                        <a class="index_table" title="View Invoice {$invoice.index_id}"
                           href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id}">{$invoice.index_id}</a>
                    {else}
                        {$invoice.index_id}
                    {/if}
                </td>
                <td class="align__text-center">{$invoice.date|utilDate}</td>
                <td>{$invoice.biller}</td>
                <td>{$invoice.customer}</td>
                <td class="align__text-right {if $invoice@last}underline{/if}">{$invoice.total|utilCurrency}</td>
            </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="align__text-right bold" colspan="4">{$LANG.totalUc}:</td>
            <td class="align__text-right bold">{$statement.total|utilCurrency}</td>
        </tr>
    </tfoot>
</table>
