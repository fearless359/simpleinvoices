{* name report_database_log.php *}
{* author Richard Rowley *}
{* license GPL V3 or above *}
{* Created: 20181108 *}
<h1 style="position: relative; margin: 0 auto; text-align: center;">Database Log Report</h1>
<hr/>
<form name="frmpost" method="post"
      action="index.php?module=reports&amp;view=report_database_log">;
    <table class="center">
        <tr>
            <td>Start date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="start_date" id="date1" value="{$start_date}"/>
            </td>
            <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
            <td>End date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                       size="10" name="end_date" id="date2" value="{$end_date}"/>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="si_center" colspan="3">
                <button type="submit" class="positive" name="submit" value="{$LANG.run_report}">
                    <img class="button_img" src="images/common/tick.png" alt=""/>
                    Run report
                </button>
            </td>
        </tr>
    </table>
</form>
<br />
<table class="center">
    <tr>
        <th colspan="3"><h2><b>Invoice Created</b></h2></th>
    </tr>
    {foreach $inserts as $rec}
    <tr>
        <td class="left" colspan="3">User {$rec.user} created invoice {$rec.last_id} on {$rec.timestamp}</td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>Invoice Updates</b></h2></th>
    </tr>
    {foreach $updates as $rec}
    <tr>
        <td>User {$rec.user} updated invoice {$rec.last_id} on {$rec.timestamp}</td>
    </tr>
    {/foreach}
</table>
<br/>
<hr/>
<table class="center">
    <tr>
        <th colspan="3"><h2><b>Payments Processed</b></h2></th>
    </tr>
    {foreach $payments as $rec}
    <tr>
        <td colspan="3">User {$rec.user} processed a payment for invoice {$rec.last_id} on {$rec.timestamp} in the amount of {$rec.amount}</td>
    </tr>
    {/foreach}
</table>
