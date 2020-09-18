<h3 class="si_report_title">{$LANG.debtors_by_aging_periods}</h3>
<hr>

<table class="si_report_table">
	<thead>
		<th colspan="10">{$LANG.debtors_by_aging_periods}</th>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">{$LANG.total_owed}</th>
			<td>{$total_owed|utilNumber:2|default:'-'}</td>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		{foreach $data as $period}
			<tr>
				<th>{$LANG.aging}:</th>
				<td colspan="9">{if isset($period.name)}{$period.name|htmlSafe}{/if}</td>
			</tr>
			<tr>
				<th>{$LANG.invoice_id|htmlSafe}</th>
				<th>{$LANG.invoice|htmlSafe}</th>
				<th>{$LANG.biller|htmlSafe}</th>
				<th>{$LANG.customer|htmlSafe}</th>
				<th>{$LANG.total|htmlSafe}</th>
				<th>{$LANG.paid|htmlSafe}</th>
				<th>{$LANG.owing_uc|htmlSafe}</th>
				<th>{$LANG.date|htmlSafe|ucfirst}</th>
				<th>{$LANG.age|htmlSafe}</th>
				<th>{$LANG.aging|htmlSafe}</th>
			</tr>

			{foreach $period.invoices as $invoice}
			<tr>
				<td>{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}</td>
				<td>{if isset($invoice.pref_inv_wording)}{$invoice.pref_inv_wording|htmlSafe}{/if} {if isset($invoice.index_id)}{$invoice.index_id|htmlSafe}{/if}</td>
				<td>{if isset($invoice.biller)}{$invoice.biller|htmlSafe}{/if}</td>
				<td>{if isset($invoice.customer)}{$invoice.customer|htmlSafe}{/if}</td>
				<td>{$invoice.inv_total|utilNumber:2|default:'-'}</td>
				<td>{$invoice.inv_paid|utilNumber:2|default:'-'}</td>
				<td>{$invoice.inv_owing|utilNumber:2|default:'-'}</td>
				<td>{if isset($invoice.date)}{$invoice.date|htmlSafe}{/if}</td>
				<td>{$invoice.age|htmlSafe}</td>
				<td>{$invoice.Aging|htmlSafe}</td>
			</tr>
			{/foreach}

			<tr>
				<th colspan="6">{$LANG.total}</th>
				<td>{$period.sum_total|utilNumber:2|default:'-'}</td>
				<td colspan="3"></td>
			</tr>

		{/foreach}
	</tbody>
</table>
