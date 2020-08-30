{*
 *  Script: add.tpl
 * 	    Cron add template
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
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=cron&amp;view=add">
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.invoice_uc}</td>
                <td>
                    <select name="invoice_id" class="validate[required]">
                        <option value=''></option>
                        {foreach $invoice_all as $invoice}
                            <option value="{$invoice.id|htmlsafe}"
                                    {if isset($smarty.post.invoice_id) &&
                                        $smarty.post.invoice_id == $invoice.id}selected{/if}>
                                {$invoice.index_name|htmlsafe}
                                ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe},
                                {$invoice.total|siLocal_number})
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.start_date}</td>
                <td>
                    <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date"
                           value="{if isset($smarty.post.start_date)}{$smarty.post.start_date}{else}{'+1 days'|date_format:'%Y-%m-%d'}{/if}"/>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.end_date}</td>
                <td>
                    <input type="text" class="date-picker" size="10" name="end_date" id="date"
                           {if isset($smarty.post.end_date)}value="{$smarty.post.end_date}"{/if}/>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.recur_each}</td>
                <td>
                    <input name="recurrence" size="10" class="validate[required]"
                           {if isset($smarty.post.recurrence)}value="{$smarty.post.recurrence}"{/if}/>
                    <select name="recurrence_type" class="validate[required]">
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
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_biller_after_cron}</td>
                <td>
                    <select name="email_biller" class="validate[required]">
                        <option value="{$smarty.const.ENABLED}"
                                {if isset($smarty.post.email_biller) &&
                                    $smarty.post.email_biller == $smarty.const.ENABELD}selected{/if}>
                                {$LANG.yes_uc}
                        </option>
                        <option value="{$smarty.const.DISABLED}"
                                {if isset($smarty.post.email_biller) &&
                                    $smarty.post.email_biller == $smarty.const.DISABLED}selected{/if}>
                            {$LANG.no_uc}
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_customer_after_cron}</td>
                <td>
                    <select name="email_customer" class="validate[required]">
                        <option value="{$smarty.const.ENABLED}"
                                {if isset($smarty.post.email_customer) &&
                                $smarty.post.email_customer == $smarty.const.ENABELD}selected{/if}>
                            {$LANG.yes_uc}
                        </option>
                        <option value="{$smarty.const.DISABLED}"
                                {if isset($smarty.post.email_customer) &&
                                $smarty.post.email_customer == $smarty.const.DISABLED}selected{/if}>
                            {$LANG.no_uc}
                    </select>
                </td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=cron&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="domain_id" value={if isset($domain_id)}{$domain_id}{/if}>
        <input type="hidden" name="op" value="add"/>
    </form>
{/if}