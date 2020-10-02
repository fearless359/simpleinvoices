{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../include/jquery/css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{include file=$path|cat:"library/dataRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <th class="details_screen">{$LANG.invoicePreferences}</th>
        <th class="details_screen align_right">{$LANG.invoiceUc}&nbsp;{$LANG.countUc}</th>
        <th class="details_screen align_right">{$LANG.totalSales}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td class="page_layer si_right" colspan="2">{$LANG.totalSales}:</td>
        <td class="page_layer si_right"><span class="bold">{$grandTotalSales|utilNumber:2|default:'-'}</span></td>
    </tr>
    </tfoot>
    <tbody>
    {foreach $data as $totalSales}
        <tr>
            <td class="align_left">{$totalSales.template|htmlSafe}</td>
            <td class="align_right">{$totalSales.count|utilNumber:0|default:'-'}</td>
            <td class="align_right">{$totalSales.total|utilNumber:2|default:'-'}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
