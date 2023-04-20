{*
 *  Script: edit.tpl
 * 	    Cron edit template
 *
 *  Last Modified:
 *      20210618 by Rich Rowley to use grid layout.
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.invoiceUc}:</div>
        <div class="cols__6-span-3">
            <a href="index.php?module=invoices&amp;view=quickView&amp;id={$cron.invoice_id|htmlSafe}">
                {$cron.index_id|htmlSafe}
            </a>
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.startDate}:</div>
        <div class="cols__6-span-3">{$cron.start_date|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.endDate}:</div>
        <div class="cols__6-span-3">{$cron.end_date|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.recurEach}:</div>
        <div class="cols__6-span-3">{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.emailBillerAfterCron}:</div>
        <div class="cols__6-span-3">
            {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yesUc}{else}{$LANG.noUc}{/if}
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-4 bold align__text-right margin__right-1">{$LANG.emailCustomerAfterCron}:</div>
        <div class="cols__6-span-3">
            {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yesUc}{else}{$LANG.noUc}{/if}
        </div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlEncode}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}" />{$LANG.edit}
    </a>
    <a href="index.php?module=cron&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
