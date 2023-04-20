{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th class="align__text-right">{$LANG.aging}</th>
			<th class="align__text-right">{$LANG.totalUc}</th>
			<th class="align__text-right">{$LANG.paidUc}</th>
			<th class="align__text-right">{$LANG.owingUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $period}
		<tr class="tr_{cycle values="A,B"}">
			<td class="align__text-right">{if isset($period.aging)}{$period.aging}{/if}</td>
			<td class="align__text-right">{$period.total|utilCurrency|default:'-'}</td>
			<td class="align__text-right">{$period.paid|utilCurrency|default:'-'}</td>
			<td class="align__text-right">{$period.owing|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td class="align__text-right">{$LANG.totalsUc}:</td>
		<td class="align__text-right">{$sumTotal|utilCurrency|default:'-'}</td>
		<td class="align__text-right">{$sumPaid|utilCurrency|default:'-'}</td>
		<td class="align__text-right">{$sumOwing|utilCurrency|default:'-'}</td>
	</tr>
	</tfoot>
</table>
