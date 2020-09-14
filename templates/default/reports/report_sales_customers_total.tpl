<h3>{$LANG.total_sales_by_customer}</h3>
<hr />

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.total_sales_by_customer}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="page_layer si_right">{$LANG.total_sales}</td>
			<td class="page_layer"><span class="BOLD">{$total_sales|utilNumber:2|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlSafe}</td>
			<td>{$customer.sum_total|utilNumber:2|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
