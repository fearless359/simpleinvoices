{if !$menu}
    <hr/>
{/if}
<div style="text-align: center;">
    <strong>
        {$LANG.totalIncome}&nbsp;{$LANG.forThePeriodUc}:&nbsp;{if isset($tot_income)}{$tot_income|utilCurrency}{/if}
    </strong>
</div>
<br/>
<table class="center" style="width:90%;">
    <thead>
    <tr style="font-weight: bold;">
        <th class="details_screen si_right" style="width:8%;">{$LANG.invoiceUc} #</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_center" style="width:10%;">{$LANG.invoiceUc} {$LANG.open} {$LANG.dateUc}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_center" style="width:23%;">{$LANG.customer}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:10%;">{$LANG.invoiceTotal}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:10%;">{$LANG.totalPaid}</th>
        <th class="details_screen" style="width:2%;"></th>
        <th class="details_screen si_right" style="width:15%;">{$LANG.totalPaidThisPeriod}</th>
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
            <td class="details_screen si_right"
                {if $invoice@last}style="text-decoration:underline;"{/if}>
                {$invoice->totalPeriodPayments|utilCurrency}
            </td>
        <tr>
        {if $display_detail}
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
        <td class="details_screen si_right">{if isset($tot_income)}{$tot_income|utilCurrency}{/if}</td>
    </tr>
    </tbody>
</table>
