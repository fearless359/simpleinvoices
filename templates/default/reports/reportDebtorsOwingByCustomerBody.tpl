{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{if $filterByDateRange == "yes"}
	{include file=$path|cat:"library/dateRangeDisplay.tpl"}
{/if}
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.customerUc}</th>
			<th class="si_right">{$LANG.totalUc}</th>
			<th class="si_right">{$LANG.paidUc}</th>
			<th class="si_right">{$LANG.owingUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $customer}
		<tr class="tr_{cycle values="A,B"}">
			<td	style="color:{if $customer.enabled == $smarty.const.ENABLED}green{else}red{/if};"}>
				{$customer.name|htmlSafe}
			</td>
			<td class="si_right">{$customer.invTotal|utilCurrency|default:'-'}</td>
			<td class="si_right">{$customer.invPaid|utilCurrency|default:'-'}</td>
			<td class="si_right">{$customer.invOwing|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<th class="si_right" colspan="3">{$LANG.totalOwing}:</th>
		<td class="si_right">{$totalOwed|utilCurrency|default:'-'}</td>
	</tr>
	</tfoot>
</table>
{if $filterByDateRange == yes}
	<div class="si_center" style="margin:0 auto; width:80%;">
		{$LANG.filterByDateRangeCaveat}
	</div>
{/if}

