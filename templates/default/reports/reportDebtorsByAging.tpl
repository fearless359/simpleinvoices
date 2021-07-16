{assign "displayExportButtonsNow" true}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
		     'fileName' => "reportDebtorsByAging",
		     'title' => $title|urlencode
		 ]
}
{include file=$path|cat:"reportDebtorsByAgingBody.tpl"}
