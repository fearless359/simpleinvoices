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
            <label for="invoiceId" class="cols__1-span-1 align__text-right margin__right-1">{$LANG.invoiceUc}:</label>
            <select name="invoice_id" id="invoiceId" class="cols__2-span-8" {if $cronInvoiceItemsCount > 0}disabled{/if} required autofocus tabindex="10"
                    onchange="$('#renderButtons').css('display','none')">
                <option value=''></option>
                {foreach $invoice_all as $invoice}
                    <option value="{if isset($invoice.id)}{$invoice.id}{/if}" {if $invoice.id == $cron.invoice_id}selected{/if}
                            data-inv-type="{if $invoice.type_id == ITEMIZED_INVOICE}ITEMIZED{else}TOTAL{/if}"">
                        {$LANG.invUc}#{$invoice.index_id}: ({$invoice.biller|htmlSafe}, {$invoice.customer|htmlSafe}, {$invoice.total|utilNumber})
                    </option>
                {/foreach}
            </select>
            {if $cronInvoiceItemsCount > 0}
            <span class="si_filters_title cols__10-span-1">
                <img class="tooltip" title="{$LANG.helpCronInvoice}" src="{$helpImagePath}help-small.png" alt=""/>
            </span>
            {/if}
        </div>
        <div class="grid__container grid__head-10">
            <label for="start_date" class="cols__1-span-4 align__text-right margin__right-1">{$LANG.startDate}:</label>
            <input type="text" name="start_date" id="start_date" value='{$cron.start_date|htmlSafe}' tabindex="20"
                   class="cols__5-span-1 date-picker" required readonly />
        </div>
        <div class="grid__container grid__head-10">
            <label for="end_date" class="cols__1-span-4 align__text-right margin__right-1">{$LANG.endDate}:</label>
            <input type="text" name="end_date" id="end_date" class="cols__5-span-1 date-picker validate-date" tabindex="30"
                   placeholder="{$PLACEHOLDERS['date']}" value='{$cron.end_date|htmlSafe}'/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="recurrenceId" class="cols__1-span-4 align__text-right margin__right-1">{$LANG.recurEach}:</label>
            <input name="recurrence" id="recurrenceId" class="cols__5-span-1" required tabindex="40"
                   value='{$cron.recurrence|htmlSafe}'/>
            <select name="recurrence_type" tabindex="50" class="cols__6-span-1 margin__left-0-5" required>
                <option value="day" {if $cron.recurrence_type == 'day'}selected{/if} >{$LANG.days}</option>
                <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
                <option value="month" {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
                <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailBillerId" class="cols__1-span-4 align__text-right margin__right-1">{$LANG.emailBillerAfterCron}:</label>
            <select name="email_biller" id="emailBillerId" class="cols__5-span-1" required>
                <option value="1" {if $cron.email_biller == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                <option value="0" {if $cron.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailCustomerId" class="cols__1-span-4 align__text-right margin__right-1">{$LANG.emailCustomerAfterCron}:</label>
            <select name="email_customer" id="emailCustomerId" class="cols__5-span-1" required>
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
{if $invoiceType == ITEMIZED_INVOICE}
    <br/>
    <br/>
    <div class="si_filters align__text-center margin__bottom-2" id="renderButtons">
        <div class="align__text-center margin__top-3 margin__bottom-3">
            <a title=" {$LANG.printUc}" class="button square" id="renderQuickViewId"
               href="index.php?module=cron&amp;view=renderQuickView&amp;cronId={$cron.id|urlencode}">
                <img src='{$path}../../../images/printer.png' class='action'  alt=""/>&nbsp; {$LANG.renderInvoice}
            </a>
            <a class="button square" id="editItemizedId"
               href="index.php?module=cron&amp;view=editItemized&amp;cronId={$cron.id}" >
                <img class="action" src="images/edit.png" alt="{$LANG.addUc}{if $cronInvoiceItemsCount > 0}&#47;{$LANG.edit}{/if} {$LANG.invoiceItems}"/>
                {$LANG.addUc}{if $cronInvoiceItemsCount > 0}&#47;{$LANG.edit}{/if} {$LANG.invoiceItems}
            </a>
            <img class="tooltip" title="{$LANG.helpCronInvoiceItems}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
    </div>
{/if}
