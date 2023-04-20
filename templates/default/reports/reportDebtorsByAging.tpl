{assign "displayExportButtonsNow" true}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
		     'fileName' => "reportDebtorsByAging",
		     'title' => $title|urlEncode
		 ]
}
{include file=$path|cat:"reportDebtorsByAgingBody.tpl"}
