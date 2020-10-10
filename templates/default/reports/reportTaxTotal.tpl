{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
		     'endDate' => $endDate|urlencode,
		     'fileName' => "reportTaxTotal",
		     'startDate' => $startDate|urlencode,
		     'title' => $title|urlencode
		 ]
}
{if $menu}
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportTaxTotal">
		<table class="center">
			{include file="templates/default/reports/library/dateRangePrompt.tpl"}
		</table>
		<br/>
		{include file="templates/default/reports/library/runReportButton.tpl" value="reportTaxTotal" label=$LANG.runReport}
		<br/>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file="templates/default/reports/reportTaxTotalBody.tpl"}
{/if}
