<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.productsSoldCustomerTotal}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $customer}
		<tr>
			<td class="bold" colspan="2">{$customer.name|htmlSafe}</td>
		</tr>
		{foreach $customer.products as $product}
			<tr>
				<td>{$product.description|htmlSafe}</td>
				<td>{$product.sum_quantity|utilNumber:0|default:'-'}</td>
			</tr>
		{/foreach}
		<tr>
			<td>{$LANG.totalUc}</td>
			<td>{$customer.total_quantity|utilNumber:0|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
