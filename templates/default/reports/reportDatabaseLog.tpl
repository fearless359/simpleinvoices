{* name reportDatabaseLog.php *}
{* author Richard Rowley *}
{* license GPL V3 or above *}
{* Created: 20181108 *}
<!--suppress HtmlFormInputWithoutLabel -->
<h1 style="position: relative; margin: 0 auto; text-align: center;">{$LANG.databaseLog} {$LANG['reportUc']}</h1>
<hr/>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=reportDatabaseLog">;
    <table class="center">
        <tr>
            <td>{$LANG.startDate}
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="start_date" id="date1" value="{if isset($start_date)}{$start_date}{/if}"/>
            </td>
            <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
            <td>{$LANG.endDate}
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="end_date" id="date2" value="{if isset($end_date)}{$end_date}{/if}"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="si_center" colspan="3">
                <button type="submit" class="positive" name="submit" value="{$LANG.runReport}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.runReport}
                </button>
            </td>
        </tr>
    </table>
</form>
<br />
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG.invoiceUc} {$LANG.createdUc}</b></h2></th>
    </tr>
    {foreach $inserts as $rec}
    <tr>
        <td class="left" colspan="3">
            {$LANG.userUc} {$rec.user} {$LANG.created} {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}
        </td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG.invoiceUc} {$LANG.updatesUc}</b></h2></th>
    </tr>
    {foreach $updates as $rec}
    <tr>
        <td>{$LANG.userUc} {$rec.user} {$LANG.updated} {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}</td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG.payments} {$LANG.processedUc}</b></h2></th>
    </tr>
    {foreach $payments as $rec}
    <tr>
        <td colspan="3">
            {$LANG.userUc} {$rec.user} {$LANG.processed}
            {$LANG.a} {$LANG.payment} {$LANG.for}
            {$LANG.invoice} {$rec.last_id} {$LANG.onLc} {$rec.timestamp}
            {$LANG.in} {$LANG.the} {$LANG.amount} {$LANG.onLc} {$rec.amount}
        </td>
    </tr>
    {/foreach}
</table>
