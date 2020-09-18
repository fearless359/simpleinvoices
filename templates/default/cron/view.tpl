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
        <th class="details_screen left">{$LANG.invoice_uc}: </th>
        <td class="si_input">
            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$cron.invoice_id|htmlSafe}">
                {$cron.index_id|htmlSafe}
            </a>
        </td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.start_date}: </th>
        <td class="si_input">{$cron.start_date|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.end_date}: </th>
        <td class="si_input">{$cron.end_date|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.recur_each}: </th>
        <td class="si_input">{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.email_biller_after_cron}: </th>
        <td class="si_input">
            {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yes_uc}{else}{$LANG.no_uc}{/if}
        </td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.email_customer_after_cron}: </th>
        <td class="si_input">
            {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yes_uc}{else}{$LANG.no_uc}{/if}
        </td>
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="positive">
        <img src="../../../images/report_edit.png" alt="" />
        {$LANG.edit}
    </a>
    <a href="index.php?module=cron&amp;view=manage" class="negative">
        <img src="../../../images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
