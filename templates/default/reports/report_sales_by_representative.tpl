<!--suppress HtmlFormInputWithoutLabel -->
<h2 class="si_report_title">{$LANG.sales_representative} {$LANG.report}</h2>
{if $menu}
    <div class="welcome">
        <form name="frmpost" method="POST" id="frmpost"
              action="index.php?module=reports&amp;view=report_Sales by Representative">
            <table class="center">
                <tr>
                    <th>{$LANG.sales_representative}</th>
                    <td>
                        <select name="sales_rep">
                            <option value="">{$LANG.unassigned}</option>
                            {foreach from=$sales_reps item=list_sales_rep}
                                <option {if $list_sales_rep == $sales_rep}selected{/if}
                                        value="{if isset($list_sales_rep)}{$list_sales_rep}{/if}">
                                    {$list_sales_rep}
                                </option>
                            {/foreach}
                        </select>
                    </td>
                    <td>&nbsp;&nbsp;</td>
                    <th>{$LANG.filter_by_dates_uc}:</th>
                    <td>
                        <input type="checkbox" name="filter_by_date" {if $filter_by_date == "yes"}checked{/if} value="yes">
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <th>{$LANG.start_date}
                        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                               name="start_date" id="date1" value='{if isset($start_date)}{$start_date|htmlSafe}{/if}'/>
                    </th>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <th>{$LANG.end_date}
                        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                               name="end_date" id="date1" value='{if isset($end_date)}{$end_date|htmlSafe}{/if}'/>
                    </th>
                </tr>
                <tr>
                    <td colspan="4">
                        <br/>
                        <table class="center">
                            <tr>
                                <td>
                                    <button type="submit" class="positive" name="submit" value="statement_report">
                                        <img class="button_img" src="../../../images/tick.png" alt=""/>
                                        {$LANG.run_report}
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    {if !isset($smarty.post.submit) && $view != export}
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    {/if}
{/if}
{if isset($smarty.post.submit) || $view == export}
    {if !$menu}
        <hr/>
        <br/>
    {/if}
    <br/>
    {if $filter_by_date == "yes"}
        <div class="align_left"><strong>{$LANG.selection_period} {if isset($start_date)}{$start_date}{/if} {$LANG.to} {if isset($end_date)}{$end_date}{/if}</strong></div>
        <br/>
    {/if}
    <table style="width:100%;">
        <tr>
            <td style="width:75%;">
                <strong>{$LANG.sales_representative}:</strong> {if isset($sales_rep)}{$sales_rep}{/if}
                <br/>
                <br/>
            </td>
            <td style="width:25%;">
                <strong>{$LANG.sales_summary}:</strong>
                <br/>
                <strong>{$LANG.total}:</strong> {$statement.total|utilNumber}
                <br/>
            </td>
        </tr>
    </table>
    <table class="center" style="width:100%;">
        <tr>
            <td class="details_screen"><b>{$LANG.id}</b></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"><b>{$LANG.date_uc}</b></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"><b>{$LANG.biller}</b></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"><b>{$LANG.customer}</b></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"><b>{$LANG.total}</b></td>
        </tr>
        {section name=invoice loop=$invoices}
            {if $smarty.section.invoice.index != 0 && $invoices[invoice].preference != $invoices[$smarty.section.invoice.index_prev].preference}
                <tr>
                    <td><br/></td>
                </tr>
            {/if}
            <tr>
                <td class="details_screen">
                    <a class="index_table" title="Edit Invoice {$invoices[invoice].index_id}"
                       href="index.php?module=invoices&amp;view=details&amp;id={$invoices[invoice].id}&amp;action=view">
                        {$invoices[invoice].index_id}
                    </a>
                </td>
                <td>&nbsp;&nbsp;</td>
                <td class="details_screen">
                    {$invoices[invoice].date|utilDate}
                </td>
                <td>&nbsp;&nbsp;</td>
                <td class="details_screen">{$invoices[invoice].biller}</td>
                <td>&nbsp;&nbsp;</td>
                <td class="details_screen">{$invoices[invoice].customer}</td>
                <td>&nbsp;&nbsp;</td>
                <td class="details_screen">{$invoices[invoice].total|utilNumber}</td>
            </tr>
        {/section}
        <tr>
            <td class="details_screen"></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen"></td>
            <td>&nbsp;&nbsp;</td>
            <td class="details_screen">-----<br/>
                {$statement.total|utilNumber}
            </td>
            <td>&nbsp;&nbsp;</td>
        </tr>
    </table>
{/if}
