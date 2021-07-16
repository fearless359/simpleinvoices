{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
        <tr>
            <th class="details_screen">{$LANG.invoicePreferences}</th>
            <th class="details_screen align__text-right">{$LANG.invoiceUc}&nbsp;{$LANG.countUc}</th>
            <th class="details_screen align__text-right">{$LANG.totalSales}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $data as $totalSales}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$totalSales.template|htmlSafe}</td>
            <td class="align__text-right">{$totalSales.count|utilNumber:0|default:'-'}</td>
            <td class="align__text-right">{$totalSales.total|utilCurrency|default:'-'}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="align__text-right bold" colspan="2">{$LANG.totalSales}:</td>
            <td class="align__text-right bold">{$grandTotalSales|utilCurrency|default:'-'}</td>
        </tr>
    </tfoot>
</table>
