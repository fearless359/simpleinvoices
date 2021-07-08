{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
{if $customFlag > 0}
    <div class="align__text-center">
        <strong>
            {$LANG.excludeUc} {$LANG.customFlagUc}:&nbsp;{$customFlag}&nbsp;-&nbsp;{$customFlagLabel}
        </strong>
    </div>
{/if}
<br/>
<div class="align__text-center">
    <strong>
        {$LANG.totalIncome}&nbsp;{$LANG.forThePeriodUc}:&nbsp;{if isset($totIncome)}{$totIncome|utilCurrency}{/if}
    </strong>
</div>
<br/>
<table class="si_report_table">
    <thead>
        <tr>
            <th class="details_screen align__text-right">{$LANG.invoiceUc} #</th>
            <th class="details_screen align__text-center">{$LANG.open} {$LANG.dateUc}</th>
            <th class="details_screen align__text-center">{$LANG.customerUc}</th>
            <th class="details_screen align__text-right">{$LANG.totalUc}</th>
            <th class="details_screen align__text-right">{$LANG.paidUc}</th>
            <th class="details_screen align__text-right">{$LANG.paidThisPeriod}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $invoices as $invoice}
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right">
                {if ($view == "reportNetIncome")}
                <a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice->id}">
                    {$invoice->indexId}
                </a>
                {else}
                    {$invoice->indexId}
                {/if}
            </td>
            <td class="align__text-center">{$invoice->date|date_format:"%m/%d/%Y"}</td>
            <td>{$invoice->customerName}</td>
            <td class="align__text-right">
                {$invoice->totalAmount|utilCurrency}
            </td>
            <td class="align__text-right">
                {$invoice->totalPayments|utilCurrency}
            </td>
            <td class="align__text-right {if $invoice@last}underline{/if}">
                {$invoice->totalPeriodPayments|utilCurrency}
            </td>
        <tr>
        {if $displayDetail == 'yes'}
            {foreach $invoice->items as $item}
                <tr class="tr_{cycle values="A,B"}">
                    <td class="align__text-right" colspan="3">{$item->description}:</td>
                    <td class="align__text-right" colspan="2">{$item->amount|utilCurrency}&nbsp;&nbsp;&nbsp;</td>
                    <td></td>
                </tr>
            {/foreach}
        {/if}
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">{$LANG.totalUc}</td>
            <td class="details_screen align__text-right">{if isset($totIncome)}{$totIncome|utilCurrency}{/if}</td>
        </tr>
    </tfoot>
</table>
