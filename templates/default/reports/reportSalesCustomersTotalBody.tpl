{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../include/jquery/css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="si_center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<div class="si_center">
	<strong>
		{$LANG.totalSales}:&nbsp;{$totalSales|utilCurrency|default:'-'}
	</strong>
</div>
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.customersUc}</th>
			<th>{$LANG.totalUc}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="si_right">{$LANG.totalUc}:</td>
			<td class="si_right"><span class="BOLD">{$totalSales|utilCurrency|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $customer}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlSafe}</td>
			<td class="si_right">{$customer.sumTotal|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
