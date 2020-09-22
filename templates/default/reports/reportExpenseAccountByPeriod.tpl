
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
  action="index.php?module=reports&amp;view=reportExpenseAccountByPeriod">
  <table class="center">
    <tr>
      <td>{$LANG.startDate}
        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
               size="10" name="start_date" id="date1" value='{$start_date}' />
      </td>
      <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
      <td>{$LANG.endDate}
        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
               size="10" name="end_date" id="date2" value='{$end_date}' />
      </td>
    </tr>
  </table>
  <br />
  <table class="center">
    <tr>
      <td>
        <button type="submit" class="positive" name="submit" value="{$LANG.insertBiller}">
          <img class="button_img" src="../../../images/tick.png" alt="" />
          {$LANG.runReport}
        </button>
      </td>
    </tr>
  </table>
</form>
<div id="top">
  <h3>{$LANG.expenseUc} {$LANG.account} {$LANG.summary} {$LANG.for}
      {$LANG['the']} {$LANG.period} {$start_date} {$LANG.to} {$end_date}</h3>
</div>
<table class="center">
  <tr>
    <td class="details_screen"><b>{$LANG.accountUc}</b></td>
    <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td class="details_screen"><b>{$LANG.amountUc}</b></td>
  </tr>
  {foreach $accounts as $account}
  <tr>
    <td class="details_screen">{$account.account}</td>
    <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
    <td class="details_screen">{$account.expense|utilNumber}</td>
  </tr>
  {/foreach}
</table>
