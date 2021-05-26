{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="si_center">{$title}</h1>
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th class="si_right">{$LANG.aging}</th>
			<th class="si_right">{$LANG.totalUc}</th>
			<th class="si_right">{$LANG.paidUc}</th>
			<th class="si_right">{$LANG.owingUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $period}
		<tr class="tr_{cycle values="A,B"}">
			<td class="si_right">{if isset($period.aging)}{$period.aging}{/if}</td>
			<td class="si_right">{$period.total|utilCurrency|default:'-'}</td>
			<td class="si_right">{$period.paid|utilCurrency|default:'-'}</td>
			<td class="si_right">{$period.owing|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td class="si_right">{$LANG.totalsUc}:</td>
		<td class="si_right">{$sumTotal|utilCurrency|default:'-'}</td>
		<td class="si_right">{$sumPaid|utilCurrency|default:'-'}</td>
		<td class="si_right">{$sumOwing|utilCurrency|default:'-'}</td>
	</tr>
	</tfoot>
</table>
