{assign 'displayExportButtonsNow' true}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'fileName' => "reportTaxVsSalesByPeriod",
             'title' => $title|urlencode
         ]
}
{include file=$path|cat:"reportTaxVsSalesByPeriodBody.tpl"}
