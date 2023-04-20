{if empty($fileType) || $fileType != 'xls' && $fileType != 'doc'}
  <link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
  <link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
<h1 class="align__text-center">{$title}</h1>
{* Display the rate column ? *}
{*{assign var=show_rates value=1}*}
{* How may decimals for rate (0-2) *}
{assign "ratePrecision" "1"}
{*-------------------------------------------------------------------------------*}
{assign var=yearsShown value=$allYears|@count}
{assign var=yearsShown value=$yearsShown-1}
{assign var=years value=$allYears.0|range:$allYears.$yearsShown}

<h2 class='si_report_title2 align__text-center'>{$LANG.salesUc}</h2>
{totals_by_period type='sales'}
{include file='templates/default/reports/reportSalesByPeriodsInclude.tpl'}

<h2 class='si_report_title2 align__text-center'>{$LANG.payments}</h2>
{totals_by_period type='payments'}
{include file='templates/default/reports/reportSalesByPeriodsInclude.tpl'}
