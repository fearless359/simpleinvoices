<table class="si_report_table">
	<thead>
		<tr>
			<th colspan="10">{$LANG.debtorsByAgingPeriods}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">{$LANG.totalOwed}</th>
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
				<th>{$LANG.invoiceId}</th>
				<th>{$LANG.invoice}</th>
				<th>{$LANG.biller}</th>
				<th>{$LANG.customer}</th>
				<th>{$LANG.totalUc}</th>
				<th>{$LANG.paidUc}</th>
				<th>{$LANG.owingUc}</th>
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
				<th colspan="6">{$LANG.totalUc}</th>
				<td>{$period.sum_total|utilNumber:2|default:'-'}</td>
				<td colspan="3"></td>
			</tr>

		{/foreach}
	</tbody>
</table>
