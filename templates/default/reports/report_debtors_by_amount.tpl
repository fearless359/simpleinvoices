<h3>{$LANG.debtors_by_amount_owed}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="7">{$LANG.debtors_by_amount_owed}</th>
		</tr>
		<tr>
			<th>{$LANG.invoice}</th>
			<th>{$LANG.biller}</th>
			<th>{$LANG.customer}</th>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owing}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" colspan="6" class="PAGE_LAYER">{$LANG.total_owed}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{$total_owed|siLocal_number:'2'|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=invoice from=$data}
		<tr>
			<td style="text-align:right;">
				<a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id}&amp;action=view">{$invoice.index_id|htmlsafe}</a>
			</td>
			<td>{$invoice.biller|htmlsafe}</td>
			<td>{$invoice.customer|htmlsafe}</td>
			<td>{$invoice.inv_total|siLocal_number:'2'|default:'0'}</td>
			<td>{$invoice.inv_paid|siLocal_number:'2'|default:'0'}</td>
			<td>{$invoice.inv_owing|siLocal_number:'2'|default:'0'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
