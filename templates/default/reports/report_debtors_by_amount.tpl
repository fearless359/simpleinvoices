<h3>{$LANG.debtors_by_amount_owed}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="7">{$LANG.debtors_by_amount_owed}</th>
		</tr>
		<tr>
			<th>{$LANG.invoice_id}</th>
			<th>{$LANG.invoice_uc}</th>
			<th>{$LANG.biller}</th>
			<th>{$LANG.customer}</th>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owing_uc}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6" class="page_layer si_right">{$LANG.total_owed}</td>
			<td class="page_layer left"><span class="bold">{$total_owed|utilNumber:2|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $invoice}
		<tr>
			<td>{$invoice.id|htmlSafe}</td>
			<td>{$invoice.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}</td>
			<td>{$invoice.biller|htmlSafe}</td>
			<td>{$invoice.customer|htmlSafe}</td>
			<td>{$invoice.inv_total|utilNumber:2|default:'0'}</td>
			<td>{$invoice.inv_paid|utilNumber:2|default:'0'}</td>
			<td>{$invoice.inv_owing|utilNumber:2|default:'0'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
