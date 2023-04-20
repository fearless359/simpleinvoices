{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
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
    <tr>
        <th class="left">{$LANG.billerUc}:</th>
        <td>{if empty($billerDetails.name)}{$LANG.allUc}{else}{$billerDetails.name|htmlSafe}{/if}</td>
        <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <th>{$LANG.totalUc}:</th>
        <td class="align__text-right">{$statement.total|utilCurrency}</td>
    </tr>
    <tr>
        <th class="left">{$LANG.customerUc}:</th>
        <td>{if empty($customerDetails.name)}{$LANG.allUc}{else}{$customerDetails.name|htmlSafe}{/if}</td>
        <td colspan="2"></td>
        <th>{$LANG.paidUc}:</th>
        <td class="align__text-right underline">{$statement.paid|utilCurrency}</td>
    </tr>
    <tr>
        <th colspan="5"></th>
        <td class="align__text-right">{$statement.owing|utilCurrency}</td>
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
    <tr>
        {if $filterByDateRange == "yes"}
            <th colspan="6">{$LANG.statementForThePeriod} {if isset($startDate)}{$startDate|htmlSafe}{/if} {$LANG.to} {if isset($endDate)}{$endDate|htmlSafe}{/if}</th>
        {/if}
    </tr>
    <tr>
        <th>&nbsp;</th>
    </tr>
</table>
    <div class="si_list">
        <table class="align__center width_100">
            <thead>
            <tr>
                <th>{$LANG.idUc}</th>
                <th>{$LANG.dateUc}</th>
                <th>{$LANG.billerUc}</th>
                <th>{$LANG.customerUc}</th>
                <th class="align__text-right">{$LANG.totalUc}</th>
                <th class="align__text-right">{$LANG.paidUc}</th>
                <th class="align__text-right">{$LANG.owingUc}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $invoices as $invoice}
                {if $invoice@index > 0 && $invoice.preference != $invoices[$invoice@index - 1].preference}
                    <tr>
                        <td><br/></td>
                    </tr>
                {/if}
                <tr>
                    <td class="align__text-right">
                        {$invoice.preference|htmlSafe}
                        {$invoice.index_id|htmlSafe}
                    </td>
                    <td class="align__text-center">{$invoice.date|utilDate}</td>
                    <td>{$invoice.biller|htmlSafe}</td>
                    <td>{$invoice.customer|htmlSafe}</td>
                    {if $invoice.status > 0}
                        <td class="align__text-right">{$invoice.total|utilCurrency}</td>
                        <td class="align__text-right">{$invoice.paid|utilCurrency}</td>
                        <td class="align__text-right">{$invoice.owing|utilCurrency}</td>
                    {else}
                        <td class="align__text-right"><i>{$invoice.total|utilCurrency}</i></td>
                        <td colspan="2">&nbsp;</td>
                    {/if}
                </tr>
            {/foreach}
            </tbody>
            <tfoot>
            <tr>
                <td colspan=3></td>
                <th></th>
                <td class="align__text-right">{$statement.total|utilCurrency}</td>
                <td class="align__text-right">{$statement.paid|utilCurrency}</td>
                <td class="align__text-right">{$statement.owing|utilCurrency}</td>
            </tr>
            </tfoot>
        </table>
    </div>
