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
			<th>{$LANG.productUc}</th>
			<th>{$LANG.countUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $customer}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$customer.description|htmlSafe}</td>
			<td class="align__text-right">{$customer.sumQuantity|utilNumber:0|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td class="align__text-right">{$LANG.totalUc}:</td>
		<td class="align__text-right bold">{$totalQuantity|utilNumber:0|default:'-'}</td>
	</tr>
	</tfoot>
</table>
