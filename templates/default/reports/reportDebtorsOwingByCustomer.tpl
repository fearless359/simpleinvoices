{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
			 'endDate' => $endDate|urlencode,
		     'fileName' => "reportDebtorsOwingByCustomer",
			 'filterByDateRange' => $filterByDateRange|urlencode,
		     'includeAllCustomers' => $includeAllCustomers|urlencode,
			 'startDate' => $startDate|urlencode,
		     'title' => $title|urlencode
		 ]
}
{if $menu}
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportDebtorsOwingByCustomer">
		<div class="si_form si_form_search">
			<div class="grid__area">
				{include file=$path|cat:"library/dateRangePrompt.tpl"}
				{include file=$path|cat:"library/filterByDateRange.tpl"}
				{include file=$path|cat:"library/includeAllCustomersPrompt.tpl"}
			    {include file=$path|cat:"library/runReportButton.tpl" value="reportDebtorsOwingByCustomer" label=$LANG.runReport}
			</div>>
		</div>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file=$path|cat:"reportDebtorsOwingByCustomerBody.tpl"}
{/if}
