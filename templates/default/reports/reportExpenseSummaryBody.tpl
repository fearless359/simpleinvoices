{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../include/jquery/css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <th>{$LANG.accountUc}</th>
        <th class="si_right">{$LANG.amountUc}</th>
        <th class="si_right">{$LANG.tax}</th>
        <th class="si_right">{$LANG.totalUc}</th>
        <th>{$LANG.status}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $accounts as $account}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$account.account}</td>
            <td class="si_right">{$account.expense|utilCurrency}</td>
            <td class="si_right">{if $account.tax != ""}{$account.tax|utilCurrency}{/if}</td>
            <td class="si_right">
                {if empty($account.total)}
                    {$account.expense|utilCurrency}
                {else}
                    {$account.total|utilCurrency}
                {/if}
            </td>
            <td>{$account.status_wording}</td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h1 class="si_center">{$title2}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <td class="si_center">{$LANG.idUc}#</td>
        <td>{$LANG.billerUc}</td>
        <td>{$LANG.customerUc}</td>
        <td class="si_right">{$LANG.amountUc}</td>
    </tr>
    </thead>
    <tbody>
    {foreach $invoices as $invoice}
        {if $invoice@index > 0 && $invoice.preference != $invoices[$invoice@index - 1].preference}
            <tr class="tr_{cycle values="A,B"}">
                <td colspan="4">&nbsp;</td>
            </tr>
        {/if}
        <tr class="tr_{cycle values="A,B"}">
            <td class="si_right">
            {$invoice.preference}
            {if $format != 'print' && $format != 'pdf' && $fileType != 'xls' && $fileType != 'doc'}
                <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                    {$invoice.index_id|htmlSafe}
                </a>
            {else}
                {$invoice.index_id}
            {/if}
            </td>
            <td>{$invoice.biller}</td>
            <td>{$invoice.customer}</td>
            <td class="si_right">{$invoice.total|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
</table>


<h1 class="si_center">{$title3}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <th>{$LANG.idUc}</th>
        <th>{$LANG.billerUc}</th>
        <th>{$LANG.customerUc}</th>
        <th>{$LANG.type}</th>
        <th class="si_right">{$LANG.amountUc}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $payments as $payment}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$payment.id}</td>
            <td>{$payment.bname}</td>
            <td>{$payment.cname}</td>
            <td>{$payment.type}</td>
            <td class="si_right">{$payment.ac_amount|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
    
