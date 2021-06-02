{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'fileName' => "reportSalesByPeriods",
             'showRates' => "{$showRates}",
             'title' => $title|urlencode
        ]
}
{if $menu}
<!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=reportSalesByPeriods">
        <div class="si_form si_form_search">
            <table class="center">
                <tr>
                    <th class="details_screen">{$LANG.showUc} {$LANG.ratesUc}:
                        <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.showUc} {$LANG.ratesUc}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpMonthlySalesAndPaymentsPerYearRates">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                        &nbsp;&nbsp;
                    </th>
                    <td>
                        <input type="checkbox" name="showRates"
                               {if isset($smarty.post.showRates) && $smarty.post.showRates == "yes"} checked {/if} value="yes"/>
                    </td>
                </tr>
            </table>
            <br/>
            {include file="templates/default/reports/library/runReportButton.tpl" value="salesByPeriods" label=$LANG.runReport}
            <br/>
        </div>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
    {include file=$path|cat:"reportSalesByPeriodsBody.tpl"}
{/if}
