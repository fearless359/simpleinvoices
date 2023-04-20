{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
<table class="si_report_table">
    <thead>
    <tr>
        <th>{$LANG.customerUc}</th>
        <th class="align__text-right">{$LANG.billed}</th>
        <th class="align__text-right">{$LANG.paidUc}</th>
        <th class="align__text-right">{$LANG.due}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $custInfo as $cust}
        <tr class="tr_{cycle values="A,B"}">
            <td>{$cust->name}</td>
            {if isset($displayDetail) && $displayDetail == 'yes'}
                <td colspan="3">&nbsp;</td>
            {else}
                <td class="align__text-right">{$cust->billed|utilCurrency}</td>
                <td class="align__text-right">{$cust->paid|utilCurrency}</td>
                <td class="align__text-right">{$cust->owed|utilCurrency}</td>
            {/if}
        </tr>
        {if isset($displayDetail) && $displayDetail == 'yes'}
            {foreach $cust->invInfo as $invInfo}
                <tr class="tr_{cycle values="A,B"}">
                    <td class="align__text-right">
                        <a href="index.php?module=invoices&amp;view=quickView&amp;id={$id}">
                            {$LANG.invoice}&nbsp;#{$invInfo->indexId}
                        </a>
                    </td>
                    <td class="align__text-right">{$invInfo->billed|utilCurrency}</td>
                    <td class="align__text-right">{$invInfo->paid|utilCurrency}</td>
                    <td class="align__text-right">{$invInfo->owed|utilCurrency}</td>
                </tr>
            {/foreach}
        {/if}
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td>{$LANG.totalsUc}</td>
            <td class="align__text-right">{$grandTotalBilled|utilCurrency}</td>
            <td class="align__text-right">{$grandTotalPaid|utilCurrency}</td>
            <td class="align__text-right">{$grandTotalOwed|utilCurrency}</td>
        </tr>
    </tfoot>
</table>
