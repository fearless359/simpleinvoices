{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
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
        <th>{$LANG.amountUc}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $accounts as $account}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$account.account}</td>
            <td class="si_right">{$account.expense|utilCurrency}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
