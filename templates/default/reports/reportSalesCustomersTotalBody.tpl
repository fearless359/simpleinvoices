{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<div class="align__text-center">
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
			<td class="align__text-right">{$LANG.totalUc}:</td>
			<td class="align__text-right"><span class="BOLD">{$totalSales|utilCurrency|default:'-'}</span></td>
		</tr>
	</tfoot>
	<tbody>
	{foreach $data as $customer}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.name|htmlSafe}</td>
			<td class="align__text-right">{$customer.sumTotal|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
