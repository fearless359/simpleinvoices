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
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlEncode}">
        <input type="hidden" name="cronId" id="cronId" value="{$cron.id|htmlSafe}">
        <div class="row">
            <div class="col__20">
                <label for="invoiceId">{$LANG.invoiceUc}:</label>
            </div>
            <div class="{if $cronInvoiceItemsCount > 0}col__75{else}col__80{/if}">
                <select name="invoice_id" id="invoiceId" class="cronInvoiceChange"
                        {if $cronInvoiceItemsCount > 0}disabled{/if} required autofocus tabindex="10">
                    <option value=''></option>
                    {foreach $invoice_all as $invoice}
                        <option value="{if isset($invoice.id)}{$invoice.id}{/if}"
                                {if $invoice.id == $cron.invoice_id}selected{/if}
                                data-locale="{$invoice.locale}" data-currency-code="{$invoice.currency_code}"
                                data-inv-type="{if $invoice.type_id == ITEMIZED_INVOICE}ITEMIZED{else}TOTAL{/if}">
                            {$LANG.invUc}#{$invoice.index_id}: ({$invoice.biller|htmlSafe},
                            {$invoice.customer|htmlSafe}, {$invoice.total|utilNumber})
                        </option>
                    {/foreach}
                </select>
            </div>
            {if $cronInvoiceItemsCount > 0}
                <div class="col__5 margin__top-1-5">
                    <span class="si_filters_title float__right">
                        <img class="tooltip" title="{$LANG.helpCronInvoice}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </span>
                </div>
            {/if}
        </div>
        <div class="row">
            <div class="col__20">
                <label for="start_date">{$LANG.startDate}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="start_date" id="start_date" value='{$cron.start_date|htmlSafe}' tabindex="20"
                       class="date-picker" required readonly/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="end_date">{$LANG.endDate}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="end_date" id="end_date" class="date-picker" tabindex="30"
                       placeholder="{$PLACEHOLDERS['date']}" value='{$cron.end_date|htmlSafe}'/>
            </div>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
        <div class="row">
            <div class="col__20">
                <label for="recurrenceId">{$LANG.recurEach}:</label>
            </div>
            <div class="col__70">
                <input type="text" name="recurrence" id="recurrenceId" class="align__text-right validateWholeNumber"
                       required tabindex="40" data-locale="{$cron.locale}" data-currency-code="{$cron.currency_code}"
                       value='{$cron.recurrence|utilNumberTrim:0}'/>
            </div>
            <div class="col__10">
                <select name="recurrence_type" style="width: 9rem;" tabindex="50" required>
                    <option value="day" {if $cron.recurrence_type == 'day'}selected{/if} >{$LANG.days}</option>
                    <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
                    <option value="month"
                            {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
                    <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="emailBillerId">{$LANG.emailBillerAfterCron}:&nbsp;</label>
            </div>
            <div class="col__80">
                <select name="email_biller" id="emailBillerId" required>
                    <option value="1"
                            {if $cron.email_biller == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                    <option value="0"
                            {if $cron.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="emailCustomerId">{$LANG.emailCustomerAfterCron}:</label>
            </div>
            <div class="col__80">
                <select name="email_customer" id="emailCustomerId" required>
                    <option value="1"
                            {if $cron.email_customer == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                    <option value="0"
                            {if $cron.email_customer == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
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
        <input type="hidden" name="domain_id" id="domain_id" value="{$cron.domain_id}">
        <input type="hidden" name="op" value="edit"/>
    </form>
    {if $invoiceType == ITEMIZED_INVOICE}
        <br/>
        <div class="si_filters align__text-center margin__bottom-2" id="renderButtons">
            <div class="align__text-center margin__top-3 margin__bottom-3">
                <a title=" {$LANG.printUc}" class="button square" id="renderQuickViewId"
                   href="index.php?module=cron&amp;view=renderQuickView&amp;cronId={$cron.id|urlEncode}">
                    <img src='images/printer.png' class='desktopOnly action' alt=""/>&nbsp;{$LANG.renderInvoice}
                </a>
                <a class="button square" id="editItemizedId"
                   href="index.php?module=cron&amp;view=editItemized&amp;cronId={$cron.id}">
                    <img src="images/edit.png" class="desktopOnly action"
                         alt="{$LANG.addUc}{if $cronInvoiceItemsCount > 0}&#47;{$LANG.edit}{/if} {$LANG.invoiceItems}"/>
                    {$LANG.addUc}{if $cronInvoiceItemsCount > 0}&#47;{$LANG.edit}{/if}&nbsp;{$LANG.invoiceItems}
                </a>
                <img class="tooltip" title="{$LANG.helpCronInvoiceItems}" src="{$helpImagePath}help-small.png" alt=""/>
            </div>
        </div>
    {/if}
</div>