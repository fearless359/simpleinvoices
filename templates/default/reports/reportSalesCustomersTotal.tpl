{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
	params=[
		'customerId' => $customerId|urlencode,
		'endDate' => $endDate|urlencode,
		'fileName' => "reportSalesCustomersTotal",
		'startDate' => $startDate|urlencode,
		'title' => $title|urlencode
	]
}
{if $menu}
	<!--suppress HtmlFormInputWithoutLabel -->
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportSalesCustomersTotal">
		<table class="center">
			{include file="templates/default/reports/library/dateRangePrompt.tpl"}
			{include file="templates/default/reports/library/customerSelectList.tpl"}
		</table>
		<br/>
		{include file="templates/default/reports/library/runReportButton.tpl" value="reportSalesCustomersTotal" label=$LANG.runReport}
		<br/>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file=$path|cat:"reportSalesCustomersTotalBody.tpl"}
{/if}
