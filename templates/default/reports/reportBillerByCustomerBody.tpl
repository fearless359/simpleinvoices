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
{assign "firstTime" true}
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.customerName}</th>
			<th>{$LANG.salesUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $biller}
		{if !$firstTime}
			<tr class="tr_{cycle values="A,B"}"><th colspan="2">&nbsp;</th></tr>
		{else}
			{assign "firstTime" false}
		{/if}
		<tr class="tr_{cycle values="A,B"}">
			<th colspan="2"><strong>{$LANG.billerName}:</strong>&nbsp;<em>{$biller.name|htmlSafe}</em></th>
		</tr>
		{foreach $biller.customers as $customer}
			<tr class="tr_{cycle values="A,B"}">
				<td>{$customer.name|htmlSafe}</td>
				<td class="si_right">{$customer.sumTotal|utilCurrency|default:'-'}</td>
			</tr>
		{/foreach}
		<tr class="tr_{cycle values="A,B"}">
			<td class="si_right bold">{$LANG.totalUc}:</td>
			<td class="si_right bold">{$biller.totalSales|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
