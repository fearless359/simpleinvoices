{assign 'displayExportButtonsNow' true}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'fileName' => "reportTaxVsSalesByPeriod",
             'title' => $title|urlEncode
         ]
}
{include file=$path|cat:"reportTaxVsSalesByPeriodBody.tpl"}
