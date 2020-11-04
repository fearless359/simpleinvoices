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
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    <table class="center">
        <tr>
            <th class="details_screen left">{$LANG.invoiceUc}:</th>
            <td>
                <select name="invoice_id" class="si_input validate[required]">
                    <option value=''></option>
                    {foreach $invoice_all as $invoice}
                        <option value="{if isset($invoice.id)}{$invoice.id}{/if}" {if $invoice.id == $cron.invoice_id}selected{/if}>
                            {$LANG.invUc}#{$invoice.index_id}: ({$invoice.biller|htmlSafe}, {$invoice.customer|htmlSafe}, {$invoice.total|utilNumber})
                        </option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th class="details_screen left">{$LANG.startDate}:</th>
            <td>
                <input type="text" class="si_input validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="start_date" id="start_date" value='{$cron.start_date|htmlSafe}'/>
            </td>
        </tr>
        <tr>
            <th class="details_screen left">{$LANG.endDate}:</th>
            <td>
                <input type="text" class="si_input date-picker" size="10" name="end_date" id="end_date"
                       value='{$cron.end_date|htmlSafe}'/>
            </td>
        </tr>
        <tr>
            <th class="details_screen left">{$LANG.recurEach}:</th>
            <td>
                <input name="recurrence" size="10" class="si_input validate[required]" value='{$cron.recurrence|htmlSafe}'/>
                <select name="recurrence_type" class="si_input validate[required]">
                    <option value="day" {if $cron.recurrence_type == 'day'}selected{/if} >{$LANG.days}</option>
                    <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
                    <option value="month" {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
                    <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
                </select>
            </td>
        </tr>
        <tr>
            <th class="details_screen left">{$LANG.emailBillerAfterCron}:</th>
            <td>
                <select name="email_biller" class="si_input validate[required]">
                    <option value="1" {if $cron.email_biller == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                    <option value="0" {if $cron.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
                </select>
            </td>
        </tr>
        <tr>
            <th class="details_screen left">{$LANG.emailCustomerAfterCron}:</th>
            <td>
                <select name="email_customer" class="si_input validate[required]">
                    <option value="1" {if $cron.email_customer == $smarty.const.ENABLED}selected{/if}>{$LANG.yesUc}</option>
                    <option value="0" {if $cron.email_customer == $smarty.const.DISABLED}selected{/if}>{$LANG.noUc}</option>
                </select>
            </td>
        </tr>
    </table>
    <br/>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}">
            <img class="button_img" src="images/tick.png" alt=""/>
            {$LANG.save}
        </button>
        <a href="index.php?module=cron&amp;view=manage" class="negative">
            <img src="images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
