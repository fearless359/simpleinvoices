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
<br/>
<br/>
<h2 style="color:red;text-align:center">
    {$LANG.selectUc} <b>{$LANG.delete}</b> {$LANG.to} {$LANG.removeThisRecord} {$LANG.andLc}
    {$LANG.its} {$LANG.associated} {$LANG.history}:
</h2>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=cron&amp;view=save&amp;id={$cron.id|urlencode}">
    <input type="hidden" name="index_id" value="{if isset($cron.index_id)}{$cron.index_id}{/if}">
    <div class="si_form">
        <table class="center">
            <tr>
                <th class="details_screen">{$LANG.invoiceUc}: </th>
                <td>
                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$cron.invoice_id|htmlSafe}">
                        {$cron.index_id|htmlSafe}
                    </a>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.startDate}: </th>
                <td>{$cron.start_date|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.endDate}: </th>
                <td>{$cron.end_date|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.recurEach}: </th>
                <td>{$cron.recurrence|htmlSafe} {$cron.recurrence_type|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.emailBillerAfterCron}: </th>
                <td>
                    {if $cron.email_biller == $smarty.const.ENABLED}{$LANG.yesUc}{/if}
                    {if $cron.email_biller == $smarty.const.DISABLED}{$LANG.noUc}{/if}
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.emailCustomerAfterCron}: </th>
                <td>
                    {if $cron.email_customer == $smarty.const.ENABLED}{$LANG.yesUc}{/if}
                    {if $cron.email_customer == $smarty.const.DISABLED}{$LANG.noUc}{/if}
                </td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.delete}">
            <img class="button_img" src="../../../images/tick.png" alt=""/>
            {$LANG.delete}
        </button>
        <a href="index.php?module=cron&amp;view=manage" class="negative">
            <img src="../../../images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="delete"/>
</form>
