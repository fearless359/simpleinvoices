{*
 *  Script: edit.tpl
 * 	    Cron edit template
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
<br/>
<table class="center" >
    <tr>
        <th class="details_screen left">{$LANG.invoiceUc}: </th>
        <td class="si_input">
            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$cron.invoice_id|htmlSafe}">
                {$cron.index_id|htmlSafe}
            </a>
        </td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.startDate}: </th>
        <td class="si_input">{$cron.start_date|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.endDate}: </th>
        <td class="si_input">{$cron.end_date|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.recurEach}: </th>
        <td class="si_input">{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.emailBillerAfterCron}: </th>
        <td class="si_input">
            {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yesUc}{else}{$LANG.noUc}{/if}
        </td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.emailCustomerAfterCron}: </th>
        <td class="si_input">
            {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yesUc}{else}{$LANG.noUc}{/if}
        </td>
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="positive">
        <img src="images/report_edit.png" alt="" />
        {$LANG.edit}
    </a>
    <a href="index.php?module=cron&amp;view=manage" class="negative">
        <img src="images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
