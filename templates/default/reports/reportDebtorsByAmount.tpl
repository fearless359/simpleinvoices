<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="7">{$LANG.debtorsByAmountOwed}</th>
		</tr>
		<tr>
			<th>{$LANG.invoiceId}</th>
			<th>{$LANG.invoiceUc}</th>
			<th>{$LANG.biller}</th>
			<th>{$LANG.customer}</th>
			<th>{$LANG.totalUc}</th>
			<th>{$LANG.paidUc}</th>
			<th>{$LANG.owingUc}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6" class="page_layer si_right">{$LANG.totalOwed}</td>
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
