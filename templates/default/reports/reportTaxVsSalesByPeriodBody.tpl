{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
<table class="si_report_table" style="width:100%">
    {foreach $years as $year}
        <thead>
        <tr>
            <th class="align__text-center bold" colspan="14">{if isset($year)}{$year}{/if}</th>
        </tr>
        </thead>
        <tbody>
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right" style="font-weight: bold;">
                {$LANG.monthUc}:
            </td>
            {foreach $totalSales.$year as $key => $sale}
                <td class="align__text-right" style="font-weight: bold;">
                    {$key}
                </td>
            {/foreach}
        </tr>
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right" style="font-weight: bold;">
                {$LANG.tax} {$LANG.onLc} {$LANG.invoices}:
            </td>
            {foreach $totalSales.$year as $key => $sale}
                <td class="align__text-right">
                    {if empty($sale|utilNumberTrim)}
                        {$sale|utilNumberTrim}
                    {else}
                        {$sale|utilCurrency}
                    {/if}
                </td>
            {/foreach}
        </tr>
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right" style="font-weight: bold;">
                {$LANG.tax} {$LANG.onLc} {$LANG.expenses}:
            </td>
            {foreach $totalPayments.$year as $key => $payment}
                <td class="align__text-right">
                    {if empty($payment|utilNumberTrim)}
                        {$payment|utilNumberTrim}
                    {else}
                        {$payment|utilCurrency}
                    {/if}
                </td>
            {/foreach}
        </tr>
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right" style="font-weight: bold;">
                {$LANG.tax} {$LANG.owing}:
            </td>
            {foreach $taxSummary.$year as $key => $summary}
                <td class="align__text-right">
                    {if empty($summary|utilNumberTrim)}
                        {$summary|utilNumberTrim}
                    {else}
                        {$summary|utilCurrency}
                    {/if}
                </td>
            {/foreach}
        </tr>
        </tbody>
    {/foreach}
</table>
