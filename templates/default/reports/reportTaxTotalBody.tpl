{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.invoiceUc}</th>
			<th>{$LANG.tax}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $invoices as $invoice}
		<tr class="tr_{cycle values="A,B"}">
			<td class="align__text-right">
			{if ($view == "reportTaxTotal")}
				<a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id}">
					{$invoice.index_id}
				</a>
			{else}
				{$invoice.index_id}
			{/if}
			</td>
			<td class="align__text-right">{$invoice.tax_amount|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td class="align__text-right bold">{$LANG.totalUc}:</td>
			<td class="align__text-right bold">{$totalTaxes|utilCurrency|default:'-'}</td>
		</tr>
	</tfoot>
</table>
