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
    <thead>
    <tr>
        <th>{$LANG.accountUc}</th>
        <th class="align__text-right">{$LANG.amountUc}</th>
        <th class="align__text-right">{$LANG.tax}</th>
        <th class="align__text-right">{$LANG.totalUc}</th>
        <th>{$LANG.status}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $accounts as $account}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$account.account}</td>
            <td class="align__text-right">{$account.expense|utilCurrency}</td>
            <td class="align__text-right">{if $account.tax != ""}{$account.tax|utilCurrency}{/if}</td>
            <td class="align__text-right">
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

<h1 class="align__text-center">{$title2}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <td class="align__text-center">{$LANG.idUc}#</td>
        <td>{$LANG.billerUc}</td>
        <td>{$LANG.customerUc}</td>
        <td class="align__text-right">{$LANG.amountUc}</td>
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
            <td class="align__text-right">
            {$invoice.preference}
            {if !isset($format)}{$fmt = ''}{else}{$fmt = $format}{/if}
            {if !isset($fileType)}{$fType = ''}{else}{$fType = $fileType}{/if}
            {if $fmt != 'print' && $fmt != 'pdf' && $fType != 'xls' && $fType != 'doc'}
                <a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id|urlEncode}">
                    {$invoice.index_id|htmlSafe}
                </a>
            {else}
                {$invoice.index_id}
            {/if}
            </td>
            <td>{$invoice.biller}</td>
            <td>{$invoice.customer}</td>
            <td class="align__text-right">{$invoice.total|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
</table>


<h1 class="align__text-center">{$title3}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
    <thead>
    <tr>
        <th>{$LANG.idUc}</th>
        <th>{$LANG.billerUc}</th>
        <th>{$LANG.customerUc}</th>
        <th>{$LANG.type}</th>
        <th class="align__text-right">{$LANG.amountUc}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $payments as $payment}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$payment.id}</td>
            <td>{$payment.bname}</td>
            <td>{$payment.cname}</td>
            <td>{$payment.type}</td>
            <td class="align__text-right">{$payment.ac_amount|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
    
