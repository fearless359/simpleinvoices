
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=report_invoice_profit">
<table class="center">
    <tr>
        <td>{$LANG['start_date']}
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                 name="start_date" id="date1" value='{if isset($start_date)}{$start_date|htmlsafe}{/if}' />
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>{$LANG['end_date']}
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                 name="end_date" id="date1" value='{if isset($end_date)}{$end_date|htmlsafe}{/if}' />
        </td>
    </tr>
</table>
<br />
<table class="center" >
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
                <img class="button_img" src="../../../images/tick.png" alt="" />
                {$LANG['run_report']}
            </button>
        </td>
    </tr>
</table>
</form>

<div id="top">
    <h3>
        {$LANG['profit']} {$LANG['per']} {$LANG['invoice']} {$LANG['based']}
        {$LANG['on']} {$LANG['average']} {$LANG['product']} {$LANG['cost']}
        {$LANG['summary']} {$LANG['for']} {$LANG['the']} {$LANG['period']}
        {if isset($start_date)}{$start_date|htmlsafe}{/if} {$LANG['to']} {if isset($end_date)}{$end_date|htmlsafe}{/if}
    </h3>
</div>

<table class="center" style="width:90%;">
    <tr>
        <th class="details_screen">{$LANG['id']}</th>
        <th class="details_screen" colspan="3">{$LANG['biller']}</th>
        <th class="details_screen" colspan="3">{$LANG['customer']}</th>
        <th class="details_screen si_right">{$LANG['total']}</th>
        <th class="details_screen si_right">{$LANG['cost_uc']}</th>
        <th class="details_screen si_right">{$LANG['profit']}</th>
	</tr>
 {section name=invoice loop=$invoices}
    {if $invoices[invoice].preference != $invoices[invoice][index_prev].preference AND $smarty.section.invoice.index != 0}
        <tr><td><br /></td></tr>
    {/if}
    <tr>
        <td class="details_screen">
            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoices[invoice].id|urlencode}">
                {if isset($index)}{$index|htmlsafe}{/if}
                {$invoices[invoice].preference|htmlsafe}
                {$invoices[invoice].index_id|htmlsafe}
            </a>
        </td>
        <td class="details_screen" colspan="3">{$invoices[invoice].biller|htmlsafe}</td>
        <td class="details_screen" colspan="3">{$invoices[invoice].customer|htmlsafe}</td>
        <td  class="details_screen si_right">{$invoices[invoice].total|siLocal_number}</td>
        <td  class="details_screen si_right">{$invoices[invoice].cost|siLocal_number}</td>
        <td  class="details_screen si_right">{$invoices[invoice].profit|siLocal_number}</td>
	</tr>
 {/section}
    <tr>
        <td class="details_screen" colspan="7"></td>
        <td class="details_screen si_right" style="font-weight:bold;">-------</td>
        <td class="details_screen si_right" style="font-weight:bold;">-------</td>
        <td class="details_screen si_right" style="font-weight:bold;">-------</td>
	</tr>
    <tr>
        <td class="details_screen si_right" colspan="7" style="font-weight:bold;">{$LANG['total']}:&nbsp;&nbsp;</td>
        <td class="details_screen si_right">{$invoice_totals.sum_total|siLocal_number}</td>
        <td class="details_screen si_right">{$invoice_totals.sum_cost|siLocal_number}</td>
        <td class="details_screen si_right">{$invoice_totals.sum_profit|siLocal_number}</td>
	</tr>
 </table>
