{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../include/jquery/css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{include file=$path|cat:"library/dataRangeDisplay.tpl"};
{if $customFlag > 0}
    <div class="si_center">
        <strong>
            {$LANG.excludeUc} {$LANG.customFlagUc}:&nbsp;{$customFlag}&nbsp;-&nbsp;{$customFlagLabel}
        </strong>
    </div>
{/if}
<br/>
<div class="si_center">
    <strong>
        {$LANG.totalIncome}&nbsp;{$LANG.forThePeriodUc}:&nbsp;{if isset($totIncome)}{$totIncome|utilCurrency}{/if}
    </strong>
</div>
<br/>
<table class="center" style="width:90%;">
    <thead>
    <tr style="font-weight: bold;">
        <th class="details_screen si_right" style="width:8%;">{$LANG.invoiceUc} #</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_center" style="width:10%;">{$LANG.open} {$LANG.dateUc}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_center" style="width:23%;">{$LANG.customer}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:10%;">{$LANG.totalUc}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:10%;">{$LANG.paidUc}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:15%;">{$LANG.paidThisPeriod}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $invoices as $invoice}
        <tr>
            <td class="details_screen si_right">
                <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice->id}&amp;action=view">
                    {$invoice->indexId}
                </a>
            </td>
            <td>&nbsp;</td>
            <td class="details_screen si_center">{$invoice->date|date_format:"%m/%d/%Y"}</td>
            <td>&nbsp;</td>
            <td class="details_screen">{$invoice->customerName}</td>
            <td>&nbsp;</td>
            <td class="details_screen si_right">
                {$invoice->totalAmount|utilCurrency}
            </td>
            <td>&nbsp;</td>
            <td class="details_screen si_right">
                {$invoice->totalPayments|utilCurrency}
            </td>
            <td>&nbsp;</td>
            <td class="details_screen si_right {if $invoice@last}underline{/if}">
                {$invoice->totalPeriodPayments|utilCurrency}
            </td>
        <tr>
        {if $displayDetail}
            {foreach $invoice->items as $item}
                <tr>
                    <td>&nbsp;</td>
                    <td class="si_right">{$LANG.descriptionUc}:</td>
                    <td colspan="4">{$item->description}</td>
                    <td class="si_right">{$LANG.amountUc}:</td>
                    <td class="si_right">{$item->amount|utilCurrency}</td>
                </tr>
            {/foreach}
        {/if}
    {/foreach}
    <tr>
        <td colspan="10">&nbsp;</td>
        <td class="details_screen si_right">{if isset($totIncome)}{$totIncome|utilCurrency}{/if}</td>
    </tr>
    </tbody>
</table>
