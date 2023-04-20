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
			<th>{$LANG.productsUc}</th>
			<th>{$LANG.countUc}</th>
		</tr>
	</thead>
	<tbody>
	{assign "lastCust" ""}
	{foreach $data as $customer}
		{if $lastCust != $customer.name}
			{if $lastCust != ""}
				<tr class="tr_{cycle values="A,B"}">
					<th colspan="2">&nbsp;</th>
				</tr>
			{/if}
			{assign "lastCust" $customer.name}
		{/if}
		<tr class="tr_{cycle values="A,B"}">
			<td class="bold" colspan="2">{$customer.name|htmlSafe}</td>
		</tr>
		{foreach $customer.products as $product}
			<tr class="tr_{cycle values="A,B"}">
				<td class="align__text-right">{$product.description|htmlSafe}</td>
				<td class="align__text-right">{$product.sum_quantity|utilNumber:0|default:'-'}</td>
			</tr>
		{/foreach}
		<tr class="tr_{cycle values="A,B"}">
			<td class="align__text-right bold">{$LANG.totalUc}:</td>
			<td class="align__text-right">{$customer.total_quantity|utilNumber:0|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
