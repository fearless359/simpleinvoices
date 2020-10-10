<!--suppress HtmlFormInputWithoutLabel -->
{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'endDate' => $endDate|urlencode,
             'fileName' => "reportSalesByRepresentative",
             'filterByDateRange' => $filterByDateRange|urlencode,
             'salesRep' => $salesRep|urlencode,
             'startDate' => $startDate|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportSalesByRepresentative">
        <table class="center">
            {include file=$path|cat:"library/dateRangePrompt.tpl"}
            {include file=$path|cat:"library/filterByDateRange.tpl"}
            <tr>
                <th>{$LANG.salesRepresentative}:</th>
                <td>
                    <select name="salesRep">
                        <option value="">{$LANG.allUc}</option>
                        {foreach $salesReps as $listSalesRep}
                            <option {if $listSalesRep == $salesRep}selected{/if}
                                    value="{$listSalesRep}">
                                {$listSalesRep}
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
        </table>
        <br/>
        {include file=$path|cat:"library/runReportButton.tpl" value="salesByRepresentative" label=$LANG.runReport}
        <br/>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportSalesByRepresentativeBody.tpl"}
{/if}
