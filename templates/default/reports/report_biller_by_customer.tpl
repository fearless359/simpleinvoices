<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.biller_sales_by_customer_totals}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $biller}
		<tr>
			<th colspan="2">{$LANG.biller_name}: {$biller.name|htmlSafe}</th>
		</tr>
		<tr>
			<th>{$LANG.customer_name}</th>
			<th>{$LANG.sales}</th>
		</tr>
		{foreach $biller.customers as $customer}
			<tr>
				<td>{$customer.name|htmlSafe}</td>
				<td>{$customer.sum_total|utilNumber:2|default:'-'}</td>
			</tr>
		{/foreach}
		<tr>
			<td>{$LANG.total}</td>
			<td>{$biller.total_sales|utilNumber:2|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
