<h3 class="si_report_title">{$LANG.total_by_aging_periods}</h3>

<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="4">{$LANG.total_by_aging_periods}</th>
		</tr>
		<tr>
			<th>{$LANG.total}</th>
			<th>{$LANG.paid}</th>
			<th>{$LANG.owing}</th>
			<th>{$LANG.aging}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>{$sum_total|siLocal_currency|default:'-'}</td>
			<td>{$sum_paid|siLocal_currency|default:'-'}</td>
			<td>{$sum_owing|siLocal_currency|default:'-'}</td>
			<td></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach item=period from=$data}
		<tr>
			<td>{$period.total|siLocal_currency|default:'-'}</td>
			<td>{$period.paid|siLocal_currency|default:'-'}</td>
			<td>{$period.owing|siLocal_currency|default:'-'}</td>
			<td>{if isset($period.aging)}{$period.aging}{/if}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
