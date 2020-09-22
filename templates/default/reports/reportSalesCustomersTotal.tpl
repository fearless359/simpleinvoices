<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.totalSalesByCustomer}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="page_layer si_right">{$LANG.totalSales}</td>
			<td class="page_layer"><span class="BOLD">{$total_sales|utilNumber:2|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $customer}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlSafe}</td>
			<td>{$customer.sum_total|utilNumber:2|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
