<h3 class="si_report_title">{$LANG.sales} {$LANG.in} {$LANG.total} {$LANG.by} {$LANG.biller}</h3>
<hr />
<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="2">{$LANG.billerSalesTotal}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="page_layer si_right">{$LANG.totalSales}</td>
			<td class="page_layer"><span class="BOLD">{$total_sales|utilNumber:2|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $biller}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$biller.name|htmlSafe}</td>
			<td>{$biller.sum_total|utilNumber:2|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
