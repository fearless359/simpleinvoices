{*
 *  Script: delete.tpl
 *      Cron delete
 *
 *  Authors:
 *      Rich Rowley
 *
 *  Last edited:
 *      2019-03-25
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<h2 class="error align__text-center">
    {$LANG.selectUc} <b>{$LANG.delete}</b> {$LANG.to} {$LANG.removeThisRecord} {$LANG.andLc}
    {$LANG.its} {$LANG.associated} {$LANG.history}:
</h2>
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.invoiceUc}: </div>
            <div class="cols__7-span-4">
                <a href="index.php?module=invoices&amp;view=quickView&amp;id={$cron.invoice_id|htmlSafe}">
                    {$cron.index_id|htmlSafe}
                </a>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.startDate}: </div>
            <div class="cols__7-span-4">{$cron.start_date|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.endDate}: </div>
            <div class="cols__7-span-4">{$cron.end_date|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.recurEach}: </div>
            <div class="cols__7-span-4">{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.emailBillerAfterCron}: </div>
            <div class="cols__7-span-4">
                {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yesUc}{/if}
                {if $cron.email_biller == $smarty.const.DISABLED}{$LANG.noUc}{/if}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-4 bold">{$LANG.emailCustomerAfterCron}: </div>
            <div class="cols__7-span-4">
                {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yesUc}{/if}
                {if $cron.email_customer == $smarty.const.DISABLED}{$LANG.noUc}{/if}
            </div>
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="submit" value="{$LANG.delete}">
            <img class="button_img" src="images/tick.png" alt="{$LANG.delete}"/>{$LANG.delete}
        </button>
        <a href="index.php?module=cron&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="delete"/>
    <input type="hidden" name="index_id" value="{if isset($cron.index_id)}{$cron.index_id}{/if}">
</form>
