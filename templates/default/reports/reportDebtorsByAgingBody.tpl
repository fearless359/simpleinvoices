{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
    <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
    <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
    <hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
<br>
<table class="si_report_table">
    <thead>
    <tr>
        <th class="align__text-right">{$LANG.invoiceUc}</th>
        <th>{$LANG.billerUc}</th>
        <th>{$LANG.customerUc}</th>
        <th class="align__text-right">{$LANG.totalUc}</th>
        <th class="align__text-right">{$LANG.paidUc}</th>
        <th class="align__text-right">{$LANG.owingUc}</th>
        <th class="align__text-center">{$LANG.dateUc}</th>
        <th class="align__text-right">{$LANG.age}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $data as $period}
        <tr class="tr_{cycle values="A,B"}">
            <td class="align__text-right bold">{$LANG.aging}:</td>
            <td class="bold" colspan="7">{if isset($period.name)}{$period.name|htmlSafe}{/if}</td>
        </tr>
        {foreach $period.invoices as $invoice}
            <tr class="tr_{cycle values="A,B"}">
                <td>
                    <a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id}">
                        {$invoice.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}
                    </a>
                </td>
                <td>{$invoice.biller|htmlSafe}</td>
                <td>{$invoice.customer|htmlSafe}</td>
                <td class="align__text-right">{$invoice.inv_total|utilCurrency|default:'-'}</td>
                <td class="align__text-right">{$invoice.inv_paid|utilCurrency|default:'-'}</td>
                <td class="align__text-right">{$invoice.inv_owing|utilCurrency|default:'-'}</td>
                <td class="align__text-center">{if isset($invoice.date)}{$invoice.date|htmlSafe}{/if}</td>
                <td class="align__text-right">{$invoice.age|htmlSafe}</td>
            </tr>
        {/foreach}
        <tr>
            <td class="align__text-right bold" colspan="5">{$LANG.totalUc}:</td>
            <td class="align__text-right bold">{$period.sum_total|utilCurrency|default:'-'}</td>
            <td colspan="2"></td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <th class="align__text-right" colspan="5">{$LANG.totalOwed}:</th>
        <th class="align__text-right">{$total_owed|utilCurrency|default:'-'}</th>
        <th colspan="2"></th>
    </tr>
    </tfoot>
</table>
