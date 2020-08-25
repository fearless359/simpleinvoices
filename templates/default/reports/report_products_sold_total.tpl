<table class="si_report_table">
	<thead>
		<tr>
			<th class="bold" colspan="2">{$LANG.products_sold_total}</th>
		</tr>
	</thead>
	<tbody>
	{foreach item=customer from=$data}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.description|htmlsafe}</td>
			<td>{$customer.sum_quantity|siLocal_number:'0'|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td class="page_layer si_right">{$LANG.total}</td>
		<td class="page_layer"><span class="bold">{$total_quantity|siLocal_number:'0'|default:'-'}</span></td>
	</tr>
	</tfoot>
</table>
