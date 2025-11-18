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
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceUc}:</div>
        <a href="index.php?module=invoices&amp;view=quickView&amp;id={$cron.invoice_id|htmlSafe}">
            {$cron.index_id|htmlSafe}
        </a>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.startDate}:</div>
        <div>{$cron.start_date|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.endDate}:</div>
        <div>{$cron.end_date|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.recurEach}:</div>
        <div>{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.emailBillerAfterCron}:</div>
        <div>
            {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yesUc}{else}{$LANG.noUc}{/if}
        </div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.emailCustomerAfterCron}:</div>
        <div>
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
