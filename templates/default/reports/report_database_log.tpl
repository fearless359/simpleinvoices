{* name report_database_log.php *}
{* author Richard Rowley *}
{* license GPL V3 or above *}
{* Created: 20181108 *}
<!--suppress HtmlFormInputWithoutLabel -->
<h1 style="position: relative; margin: 0 auto; text-align: center;">{$LANG['database_log']} {$LANG['report']}</h1>
<hr/>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=report_database_log">;
    <table class="center">
        <tr>
            <td>{$LANG['start_date']}
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="start_date" id="date1" value="{if isset($start_date)}{$start_date}{/if}"/>
            </td>
            <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
            <td>{$LANG['end_date']}
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="end_date" id="date2" value="{if isset($end_date)}{$end_date}{/if}"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="si_center" colspan="3">
                <button type="submit" class="positive" name="submit" value="{$LANG.run_report}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG['run_report']}
                </button>
            </td>
        </tr>
    </table>
</form>
<br />
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG['invoice_uc']} {$LANG['created_uc']}</b></h2></th>
    </tr>
    {foreach $inserts as $rec}
    <tr>
        <td class="left" colspan="3">
            {$LANG['user_uc']} {$rec.user} {$LANG['created']} {$LANG['invoice']} {$rec.last_id} {$LANG['on']} {$rec.timestamp}
        </td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG['invoice_uc']} {$LANG['updates_uc']}</b></h2></th>
    </tr>
    {foreach $updates as $rec}
    <tr>
        <td>{$LANG['user_uper']} {$rec.user} {$LANG['updated']} {$LANG['invoice']} {$rec.last_id} {$LANG['on']} {$rec.timestamp}</td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>{$LANG['payments']} {$LANG['processed_uc']}</b></h2></th>
    </tr>
    {foreach $payments as $rec}
    <tr>
        <td colspan="3">
            {$LANG['user_uc']} {$rec.user} {$LANG['processed']}
            {$LANG['a']} {$LANG['payment']} {$LANG['for']}
            {$LANG['invoice']} {$rec.last_id} {$LANG['on']} {$rec.timestamp}
            {$LANG['in']} {$LANG['the']} {$LANG['amount']} {$LANG['on']} {$rec.amount}
        </td>
    </tr>
    {/foreach}
</table>
