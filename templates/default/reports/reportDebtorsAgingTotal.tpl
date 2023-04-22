{assign "displayExportButtonsNow" true}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
             'fileName' => "reportDebtorsAgingTotal",
		     'title' => $title|urlencode
		 ]
}
{include file=$path|cat:"reportDebtorsAgingTotalBody.tpl"}
