<div class="si_center"><h2>Net Income Report</h2></div>
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=report_net_income">
        <table class="center">
            <tr>
                <td colspan="2"
                    style="font-weight: bold; font-size: 1.5em; text-align: center; text-decoration: underline;">
                    Report Period
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr style="margin: 0 auto; width: 100%;">
                <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    Start date:
                </td>
                <td>
                    <input type="text"
                           class="validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="start_date" id="date1" value='{if isset($start_date)}{$start_date}{/if}'/>
                </td>
            </tr>
            <tr style="margin: 0 auto; width: 100%;">
                <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    End date:
                </td>
                <td>
                    <input type="text"
                           class="validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="end_date" id="date2" value='{if isset($end_date)}{$end_date}{/if}'/>
                </td>
            </tr>
            <tr>
                <td class="details_screen" style="text-align:right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    Customers:
                </td>
                <td>
                    <select name="customer_id">
                        <option {if empty($customer_id)}selected{/if} value="0">All Customers</option>
                        {foreach from=$customers item=customer}
                            <option {if $customer.id == $customer_id}selected{/if} value="{$customer.id}">{$customer.name} (Last Activity: {$customer.last_activity_date})</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    Exclude Custom Flag #:
                </td>
                <td>
                    <select name="custom_flag">
                        <option value="0">None</option>
                        {foreach from=$custom_flag_labels key=ndx item=label}
                            {if $label != ''}
                                <option value="{$ndx+1}" {if $custom_flag - 1 == $ndx} selected {/if}>
                                    {$ndx+1}&nbsp;-&nbsp;{$label}
                                </option>
                            {/if}
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="details_screen" style="text-align:right; padding-right: 10px; white-space: nowrap; width: 47%;">
                    Display Detail:
                </td>
                <td><input type="checkbox" name="display_detail"
                            {if isset($smarty.post.display_detail) && $smarty.post.display_detail == "yes"} checked {/if} value="yes"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br/>
                    <table class="center">
                        <tr>
                            <td>
                                <button type="submit" class="positive" name="submit" value="statement_report">
                                    <img class="button_img" src="images/common/tick.png" alt=""/>
                                    Run Report
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br/>
    </form>
{/if}
{if isset($smarty.post.submit) || $view == export}
    {if !$menu}
        <hr/>
    {/if}
    <div style="text-align: center;">
        <strong>
            {$LANG.total_income}&nbsp;{$LANG.for_the_period_upper}:&nbsp;&#36;{if isset($tot_income)}{$tot_income|siLocal_number}{/if}
        </strong>
    </div>
    <br/>
    <table class="center" style="width:90%">
        <thead>
        <tr style="font-weight: bold;">
            <th class="details_screen" width="8%" style="text-align:right;">{$LANG.invoice}</th>
            <th class="details_screen" width="2%"></th>
            <th class="details_screen" width="12%" style="text-align:center;">{$LANG.date_upper}</th>
            <th class="details_screen" width="2%"></th>
            <th class="details_screen" style="text-align:center;">{$LANG.customer}</th>
            <th class="details_screen" width="2%"></th>
            <th class="details_screen" width="13%" style="text-align:right;">{$LANG.invoice_total}</th>
            <th class="details_screen" width="2%"></th>
            <th class="details_screen" width="13%" style="text-align:right;">{$LANG.total_paid}</th>
            <th class="details_screen" width="2%"></th>
            <th class="details_screen" width="13%" style="text-align:right;">{$LANG.total_paid_this_period}</th>
        </tr>
        </thead>
        <tbody>
        {section name=idx loop=$invoices}
            <tr>
                <td class="details_screen" style="text-align:right;">
                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoices[idx]->id}&amp;action=view">
                        {$invoices[idx]->number}
                    </a>
                </td>
                <td>&nbsp;</td>
                <td class="details_screen" style="text-align:center;">{$invoices[idx]->date|date_format:"%m/%d/%Y"}</td>
                <td>&nbsp;</td>
                <td class="details_screen">{$invoices[idx]->customer}</td>
                <td>&nbsp;</td>
                <td class="details_screen" style="text-align:right;">
                    {$invoices[idx]->total_amount|siLocal_number}
                </td>
                <td>&nbsp;</td>
                <td class="details_screen" style="text-align:right;">
                    {$invoices[idx]->total_payments|siLocal_number}
                </td>
                <td>&nbsp;</td>
                <td class="details_screen"
                    style="text-align:right;{if $smarty.section.idx.last}text-decoration:underline;{/if}">
                    {$invoices[idx]->total_period_payments|siLocal_number}
                </td>
            <tr>
            {if $display_detail}
                {foreach $invoices[idx]->items as $item}
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align:right;">Description:</td>
                        <td colspan="4">{$item->description}</td>
                        <td style="text-align:right;">Amount:</td>
                        <td style="text-align:right;">{$item->amount|siLocal_number}</td>
                    </tr>
                {/foreach}
            {/if}
        {/section}

        <tr>
            <td colspan="10">&nbsp;</td>
            <td class="details_screen" style="text-align:right;">&#36;{if isset($tot_income)}{$tot_income|siLocal_number}{/if}</td>
        </tr>

        </tbody>
    </table>
{/if}
