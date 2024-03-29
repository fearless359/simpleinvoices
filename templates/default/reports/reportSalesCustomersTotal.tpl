{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
		     'customerId' => $customerId|urlEncode,
		     'endDate' => $endDate|urlEncode,
		     'fileName' => "reportSalesCustomersTotal",
		     'startDate' => $startDate|urlEncode,
		     'title' => $title|urlEncode
		 ]
}
{if $menu}
	<!--suppress HtmlFormInputWithoutLabel -->
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportSalesCustomersTotal&amp;showAllReports={$showAllReports}">
		<div class="si_form si_form_search">
			<div class="grid__area">
				{include file=$path|cat:"library/dateRangePrompt.tpl"}
				{include file=$path|cat:"library/customerSelectList.tpl"}
				{include file=$path|cat:"library/runReportButton.tpl" value="reportSalesCustomersTotal" label=$LANG.runReport}
			</div>
		</div>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file=$path|cat:"reportSalesCustomersTotalBody.tpl"}
{/if}
