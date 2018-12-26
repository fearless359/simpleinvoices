{*
 *  Script: delete.tpl
 *      Cron delete
 *
 *  Authors:
 *      Rich Rowley
 *
 *  Last edited:
 *      2016-08-08
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<br/>
<br/>
<h3 style="text-align:center">Select <b>Delete</b> to remove this record:</h3>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    <input type="hidden" name="index_id" value="{if isset($cron.index_id)}{$cron.index_id}{/if}">
    <div class="si_form si_form_view">
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.invoice}</td>
                <td>{$cron.invoice_id|htmlsafe}</td>
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
                    {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yes_uppercase}{/if}
                    {if $cron.email_biller == $smarty.const.DISABLED}{$LANG.no_uppercase}{/if}
                </td>
            </tr>
            <tr>
                <td class="details_screen">{$LANG.email_customer_after_cron}</td>
                <td>
                    {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yes_uppercase}{/if}
                    {if $cron.email_customer == $smarty.const.DISABLED}{$LANG.no_uppercase}{/if}
                </td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="id" value="{$LANG.delete}">
            <img class="button_img" src="images/common/tick.png" alt=""/>
            {$LANG.delete}
        </button>
        <a href="index.php?module=cron&amp;view=manage" class="negative">
            <img src="images/common/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="delete"/>
</form>
