{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="si_center">{$title}</h1>
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.invoiceUc}</th>
			<th>{$LANG.billerUc}</th>
			<th>{$LANG.customerUc}</th>
			<th>{$LANG.totalUc}</th>
			<th>{$LANG.paidUc}</th>
			<th>{$LANG.owingUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $invoice}
		<tr class="tr_{cycle values="A,B"}">
			<td>
				<a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id}">
					{$invoice.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}
				</a>
			</td>
			<td>{$invoice.billerName|htmlSafe}</td>
			<td>{$invoice.customerName|htmlSafe}</td>
			<td class="si_right">{$invoice.invTotal|utilCurrency|default:'0'}</td>
			<td class="si_right">{$invoice.invPaid|utilCurrency|default:'0'}</td>
			<td class="si_right">{$invoice.invOwing|utilCurrency|default:'0'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td colspan="5" class="si_right">{$LANG.totalOwed}:</td>
		<td class="si_right"><span class="bold">{$totalOwed|utilCurrency|default:'-'}</span></td>
	</tr>
	</tfoot>
</table>
