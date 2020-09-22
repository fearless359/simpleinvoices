<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="4">{$LANG.totalByAgingPeriods}</th>
		</tr>
		<tr>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owingUc}</th>
			<th>{$LANG.aging}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>{$sum_total|utilCurrency|default:'-'}</td>
			<td>{$sum_paid|utilCurrency|default:'-'}</td>
			<td>{$sum_owing|utilCurrency|default:'-'}</td>
			<td></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $period}
		<tr>
			<td>{$period.total|utilCurrency|default:'-'}</td>
			<td>{$period.paid|utilCurrency|default:'-'}</td>
			<td>{$period.owing|utilCurrency|default:'-'}</td>
			<td>{if isset($period.aging)}{$period.aging}{/if}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
