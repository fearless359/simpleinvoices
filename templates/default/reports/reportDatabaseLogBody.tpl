{if $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center margin__bottom-2">{$title}</h1>
<table class="si_report_table">
    <thead>
    <tr>
        <th colspan="3"><h2><b>{$LANG.invoiceUc} {$LANG.createdUc}</b></h2></th>
    </tr>
    </thead>
    <tbody>
    {foreach $inserts as $rec}
        <tr class="tr_{cycle values="A,B"}">
            <td class="left" colspan="3">
                {$LANG.userUc} {$rec.user} {$LANG.created} {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
<br/>
<hr/>
<table class="si_report_table">
    <thead>
    <tr>
        <th colspan="3"><h2><b>{$LANG.invoiceUc} {$LANG.updatesUc}</b></h2></th>
    </tr>
    </thead>
    <tbody>
    {foreach $updates as $rec}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$LANG.userUc} {$rec.user} {$LANG.updated} {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
<br/>
<hr/>
<table class="si_report_table">
    <thead>
    <tr>
        <th colspan="3"><h2><b>{$LANG.payments} {$LANG.processedUc}</b></h2></th>
    </tr>
    </thead>
    <tbody>
    {foreach $payments as $rec}
        <tr class="tr_{cycle values="A,B"}">
            <td colspan="3">
                {$LANG.userUc} {$rec.user} {$LANG.processed}
                {$LANG.a} {$LANG.payment} {$LANG.for}
                {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}
                {$LANG.in} {$LANG.the} {$LANG.amount} {$LANG.onLc} {$rec.amount}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
