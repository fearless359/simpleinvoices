{*
 *  Script: create.tpl
 * 	    Cron add template
 *
 *  Last Modified:
 *      20251222 by Rich Rowley to add option to return all invoices for dropdown.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210618 by Rich Rowley to use grid layout.
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
{if !empty($smarty.post.invoice_id)}
    {include file="templates/default/cron/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=cron&amp;view=create">
            {if $invoiceDisplayDays != 0}
                <div class="row">
                    <div class="col__100 align__text-right">
                        <a href="index.php?module=cron&amp;view=create&amp;all_invoices=1" class="si_filters_links"
                           style="padding: 1rem;">
                            {$LANG.returnUc}&nbsp;{$LANG.allUc}&nbsp;{$LANG.invoicesUc}
                        </a>
                    </div>
                </div>
            {/if}
            <div class="row">
                <div class="col__20">
                    <label for="invoiceId">{$LANG.invoiceUc}:</label>
                </div>
                <div class="col__80">
                    <select name="invoice_id" id="invoiceId" class="cronInvoiceChange" required autofocus tabindex="10">
                        <option value=''></option>
                        {foreach $invoice_all as $invoice}
                            <option value="{$invoice.id|htmlSafe}"
                                    data-locale="{$invoice.locale}" data-currency-code="{$invoice.currency_code}"
                                    {if isset($smarty.post.invoice_id) &&
                                    $smarty.post.invoice_id == $invoice.id}selected{/if}>
                                {$invoice.index_name|htmlSafe}
                                ({$invoice.biller|htmlSafe}, {$invoice.customer|htmlSafe},
                                {$invoice.total|utilNumber})
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="startdate">{$LANG.startDate}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="start_date" id="startdate" tabindex="20"
                           placeholder="{$PLACEHOLDERS['date']}" class="date-picker" readonly required
                           value="{if isset($smarty.post.start_date)}{$smarty.post.start_date}{else}{'+1 days'|date_format:'%Y-%m-%d'}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="enddate">{$LANG.endDate}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="end_date" id="enddate" class="date-picker" tabindex="30"
                           placeholder="{$PLACEHOLDERS['date']}"
                           {if isset($smarty.post.end_date)}value="{$smarty.post.end_date}"{/if}/>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <div class="row">
                <div class="col__20">
                    <label for="recurrenceId">{$LANG.recurEach}:</label>
                </div>
                <div class="col__70">
                    <input type="text" name="recurrence" id="recurrenceId" class="validateWholeNumber" required
                           tabindex="40"
                           {if isset($smarty.post.recurrence)}value="{$smarty.post.recurrence|utilNumberTrim:0}"{/if}/>
                </div>
                <div class="col__10">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <select name="recurrence_type" style="width: 9rem;" required tabindex="50">
                        <option value="day"
                                {if isset($smarty.post.recurrence_type) &&
                                $smarty.post.recurrence_type == 'day'}selected{/if})>{$LANG.days}</option>
                        <option value="week"
                                {if isset($smarty.post.recurrence_type) &&
                                $smarty.post.recurrence_type == 'week'}selected{/if})>{$LANG.weeks}</option>
                        <option value="month"
                                {if isset($smarty.post.recurrence_type) &&
                                $smarty.post.recurrence_type == 'month'}selected{/if})>{$LANG.months}</option>
                        <option value="year"
                                {if isset($smarty.post.recurrence_type) &&
                                $smarty.post.recurrence_type == 'year'}selected{/if})>{$LANG.years}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="emailBillerId">{$LANG.emailBillerAfterCron}:</label>
                </div>
                <div class="col__80">
                    <select name="email_biller" id="emailBillerId" required tabindex="60">
                        <option value="{$smarty.const.ENABLED}"
                                {if isset($smarty.post.email_biller) &&
                                $smarty.post.email_biller == $smarty.const.ENABELD}selected{/if}>{$LANG.yesUc}</option>
                        <option value="{$smarty.const.DISABLED}"
                                {if isset($smarty.post.email_biller) &&
                                $smarty.post.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="emailCustomerId">{$LANG.emailCustomerAfterCron}:</label>
                </div>
                <div class="col__80">
                    <select name="email_customer" id="emailCustomerId" required tabindex="70">
                        <option value="{$smarty.const.ENABLED}"
                                {if isset($smarty.post.email_customer) &&
                                $smarty.post.email_customer == $smarty.const.ENABELD}selected{/if}>{$LANG.yesUc}</option>
                        <option value="{$smarty.const.DISABLED}"
                                {if isset($smarty.post.email_customer) &&
                                $smarty.post.email_customer == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
                    </select>
                </div>
            </div>
            <div class="align__text-center margin__top-3 margin__bottom-2">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=cron&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="domain_id" value={if isset($domain_id)}{$domain_id}{/if}>
            <input type="hidden" name="op" value="create"/>
        </form>
    </div>
{/if}
