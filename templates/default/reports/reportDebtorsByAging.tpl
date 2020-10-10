{*{include file=$path|cat:"library/reportTitle.tpl" title=$title}*}
{assign "displayExportButtonsNow" true}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
		     'fileName' => "reportDebtorsByAging",
		     'title' => $title|urlencode
		 ]
}
{*{if $menu}*}
{*	<form name="frmpost" method="POST" id="frmpost"*}
{*		  action="index.php?module=reports&amp;view=reportDebtorsByAging">*}
{*		{include file=$path|cat:"library/runReportButton.tpl" value="reportDebtorsByAging" label=$LANG.runReport}*}
{*		<br/>*}
{*	</form>*}
{*{/if}*}
{*{if isset($smarty.post.submit) || $view == "export"}*}
	{include file=$path|cat:"reportDebtorsByAgingBody.tpl"}
{*{/if}*}
