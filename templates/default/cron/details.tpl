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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    {if $smarty.get.action== 'view' }
        <br/>
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.invoice}</td>
                <td>
                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$cron.invoice_id|htmlsafe}">
                        {$cron.index_name|htmlsafe}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.start_date}</td>
                <td>{$cron.start_date|htmlsafe}</td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.end_date}</td>
                <td>{$cron.end_date|htmlsafe}</td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.recur_each}</td>
                <td>{$cron.recurrence|htmlsafe} {$cron.recurrence_type|htmlsafe}</td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_biller_after_cron}</td>
                <td>
                    {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yes_uppercase}{else}{$LANG.no_uppercase}{/if}
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_customer_after_cron}</td>
                <td>
                    {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yes_uppercase}{else}{$LANG.no_uppercase}{/if}
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=cron&amp;view=details&amp;action=edit&amp;id={$cron.id|urlencode}" class="positive">
                <img src="images/famfam/report_edit.png" alt="" />
                {$LANG.edit}
            </a>
            <a href="index.php?module=cron&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        {* ######################################################################################### *}
    {elseif $smarty.get.action== 'edit' }
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.invoice}</td>
                <td>
                    <select name="invoice_id" class="validate[required]">
                        <option value=''></option>
                        {foreach $invoice_all as $invoice}
                            <option value="{if isset($invoice.id)}{$invoice.id|htmlsafe}{/if}" {if $invoice.id == $cron.invoice_id}selected{/if} >
                                Inv#{$invoice.index_id}: ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe}, {$invoice.total|siLocal_number})
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.start_date}</td>
                <td>
                    <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="start_date" id="date" value='{$cron.start_date|htmlsafe}'/>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.end_date}</td>
                <td>
                    <input type="text" class="date-picker" size="10" name="end_date" id="date" value='{$cron.end_date|htmlsafe}'/>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.recur_each}</td>
                <td>
                    <input name="recurrence" size="10" class="validate[required]" value='{$cron.recurrence|htmlsafe}'/>
                    <select name="recurrence_type" class="validate[required]">
                        <option value="day" {if $cron.recurrence_type == 'day'}selected{/if} >{$LANG.days}</option>
                        <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
                        <option value="month" {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
                        <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_biller_after_cron}</td>
                <td>
                    <select name="email_biller" class="validate[required]">
                        <option value="1" {if $cron.email_biller == $smarty.const.ENABLED}selected{/if}>{$LANG.yes_uppercase}</option>
                        <option value="0" {if $cron.email_biller == $smarty.const.DISABLED}selected{/if}>{$LANG.no_uppercase}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_customer_after_cron}</td>
                <td>
                    <select name="email_customer" class="validate[required]">
                        <option value="1" {if $cron.email_customer == $smarty.const.ENABLED}selected{/if}>{$LANG.yes_uppercase}</option>
                        <option value="0" {if $cron.email_customer == $smarty.const.DISABLED}selected{/if}>{$LANG.no_uppercase}</option>
                    </select>
                </td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="id" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=cron&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
    {/if}
</form>
