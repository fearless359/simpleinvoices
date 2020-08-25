<!--suppress HtmlFormInputWithoutLabel -->
<h1 style="position: relative; margin: 0 auto; text-align: center;">
  30 {$LANG['days_uc']} {$LANG['or']} {$LANG['more']} {$LANG['past_due_report']}
</h1>
<hr />
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=report_past_due" >
  <table class="center" >
    <tr>
      <td class="details_screen">{$LANG['display']} {$LANG['detail']}</td>
      <td><input type="checkbox" name="display_detail"
                {if isset($smarty.post.display_detail) && $smarty.post.display_detail == "yes"} checked {/if} value="yes" />
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <table class="center">
          <tr>
            <td>
              <button type="submit" class="positive" name="submit" value="report_past_due">
                <img class="button_img" src="../../../images/tick.png" alt="" />
                {$LANG['run_report']}
              </button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<br/>
<br/>
<table class="center" style="width:60%;;">
  <thead>
    <tr style="font-weight: bold;">
      <th class="details_screen">{$LANG.customer}</th>
      <th class="details_screen" style="width:2%;"></th>
      <th class="details_screen si_right" style="width:10%;">{$LANG.billed}</th>
      <th class="details_screen" style="width:2%;"></th>
      <th class="details_screen si_right" style="width:10%;">{$LANG.paid}</th>
      <th class="details_screen" style="width:2%;"></th>
      <th class="details_screen si_right" style="width:10%;">{$LANG.due}</th>
    </tr>
  </thead>
  <tbody>
  {foreach name=loop1 item=info1 from=$cust_info}
    {foreach name=loop2 item=info2 key=key2 from=$info1}
      {if     $key2=='name'      }{assign var=name    value=$info2}
      {elseif $key2=='fmtdBilled'}{assign var=billed  value=$info2}
      {elseif $key2=='fmtdPaid'  }{assign var=paid    value=$info2}
      {elseif $key2=='fmtdOwed'  }{assign var=owed    value=$info2}
      {elseif $key2=='invInfo'   }{assign var=invInfo value=$info2}
      {/if}
    {/foreach}
    <tr>
      <td class="details_screen">{if isset($name)}{$name}{/if}</td>
      {if isset($smarty.post.display_detail) && $smarty.post.display_detail == 'yes'}
        <td colspan="6">&nbsp;</td>
      {else}
        <td>&nbsp;</td>
        <td class="details_screen si_right">{if isset($billed)}{$billed}{/if}</td>
        <td>&nbsp;</td>
        <td class="details_screen si_right">{if isset($paid)}{$paid}{/if}</td>
        <td>&nbsp;</td>
        <td class="details_screen si_right">{if isset($owed)}{$owed}{/if}</td>
      {/if}
    </tr>
    {if isset($smarty.post.display_detail) && $smarty.post.display_detail == 'yes'}
      {foreach name=loop2 item=info2 from=$invInfo}
        {foreach name=loop3 item=info3 key=key3 from=$info2}
          {if     $key3=='id'        }{assign var=id       value=$info3}
          {elseif $key3=='indexId'   }{assign var=index_id value=$info3}
          {elseif $key3=='fmtdBilled'}{assign var=billed   value=$info3}
          {elseif $key3=='fmtdPaid'  }{assign var=paid     value=$info3}
          {elseif $key3=='fmtdOwed'  }{assign var=owed     value=$info3}
          {/if}
        {/foreach}    
        <tr>
          <td class="details_screen" style="margin-left:20%;">
            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$id}&amp;action=view">{$LANG['invoice']}&nbsp;#{$index_id}</a>
          </td>
          <td>&nbsp;</td>
          <td class="details_screen si_right" style="margin-right:auto;">{if isset($billed)}{$billed}{/if}</td>
          <td>&nbsp;</td>
          <td class="details_screen si_right" style="margin-right:auto;">{if isset($paid)}{$paid}{/if}</td>
          <td>&nbsp;</td>
          <td class="details_screen si_right" style="margin-right:auto;">{if isset($owed)}{$owed}{/if}</td>
          <td>&nbsp;</td>
        </tr>
      {/foreach}
    {/if}
  {/foreach}
  </tbody>
</table>
