{*
 *  Script: edit.tpl
 * 	    Cron edit template
 *
 * Last edited:
 *      2021-06-17 by Rich Rowley
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="invoiceId" class="cols__1-span-1">{$LANG.invoiceUc}:</label>
            <select name="invoice_id" id="invoiceId" class="cols__2-span-9 validate[required]" autofocus tabindex="10">
                <option value=''></option>
                {foreach $invoice_all as $invoice}
                    <option value="{if isset($invoice.id)}{$invoice.id}{/if}" {if $invoice.id == $cron.invoice_id}selected{/if}>
                        {$LANG.invUc}#{$invoice.index_id}: ({$invoice.biller|htmlSafe}, {$invoice.customer|htmlSafe}, {$invoice.total|utilNumber})
                    </option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="start_date" class="cols__1-span-3">{$LANG.startDate}:</label>
            <input type="text" name="start_date" id="start_date" value='{$cron.start_date|htmlSafe}' tabindex="20"
                   class="cols__5-span-1 validate[required,custom[date],length[0,10]] date-picker"/>

            <label for="end_date" class="cols__7-span-3">{$LANG.endDate}:</label>
            <input type="text" name="end_date" id="end_date" class="cols__10-span-1 date-picker" tabindex="30"
                   value='{$cron.end_date|htmlSafe}'/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="recurrenceId" class="cols__1-span-3">{$LANG.recurEach}:</label>
            <input name="recurrence" id="recurrenceId" class="cols__4-span-2 validate[required]" tabindex="40"
                   value='{$cron.recurrence|htmlSafe}'/>
            <select name="recurrence_type" tabindex="50" class="cols__6-span-2 margin__left-0-5 validate[required]">
                <option value="day" {if $cron.recurrence_type == 'day'}selected{/if} >{$LANG.days}</option>
                <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
                <option value="month" {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
                <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailBillerId" class="cols__1-span-3">{$LANG.emailBillerAfterCron}:</label>
            <select name="email_biller" id="emailBillerId" class="cols__4-span-1 validate[required]">
                <option value="1" {if $cron.email_biller == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                <option value="0" {if $cron.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
            </select>

            <label for="emailCustomerId" class="cols__6-span-4 bold">{$LANG.emailCustomerAfterCron}:</label>
            <select name="email_customer" id="emailCustomerId" class="cols__10-span-1 validate[required]">
                <option value="1" {if $cron.email_customer == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                <option value="0" {if $cron.email_customer == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
            </select>
        </div>
    </div>
    <br/>
    <div class="align__text-center">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=cron&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt=""/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
