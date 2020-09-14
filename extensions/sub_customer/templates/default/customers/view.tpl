{*
 * Script: details.tpl
 *      Customer details template
 *
 * Last modified:
 *      2018-12-21 by Richard Rowley
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<br/>
<div class="si_form" id="si_form_cust">
    <div class="si_cust_info">
        <table class="center">
            <tr>
                <th class="details_screen">{$LANG.customer_name}:</th>
                <td>{$customer.name}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.customer_department}:</th>
                <td>{$customer.department|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.attention_short}:</th>
                <td>{$customer.attention|htmlSafe}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.phone}:</th>
                <td>{$customer.phone|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.street}:</th>
                <td>{$customer.street_address|htmlSafe}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.mobile_phone}:</th>
                <td>{$customer.mobile_phone|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.street2}:</th>
                <td>{$customer.street_address2|htmlSafe}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.fax}:</th>
                <td>{$customer.fax|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.city}:</th>
                <td>{$customer.city|htmlSafe}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.email}:</th>
                <td><a href="mailto:{$customer.email|htmlSafe}">{$customer.email|htmlSafe}</a></td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.zip}:</th>
                <td>{$customer.zip_code|htmlSafe}</td>
                <td class="td_sep"></td>
                <th class="details_screen">{$LANG.default_invoice}:</th>
                <td>{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.state}:</th>
                <td>{$customer.state|htmlSafe}</td>
                {if !empty($customFieldLabel.customer_cf1)}
                    <td class="td_sep"></td>
                    <th class="details_screen">{$customFieldLabel.customer_cf1}:</th>
                    <td>{$customer.custom_field1|htmlSafe}</td>
                {else}
                    <td colspan="3"></td>
                {/if}
            </tr>
            <tr>
                <th class="details_screen">{$LANG.country}:</th>
                <td>{$customer.country|htmlSafe}</td>
                {if !empty($customFieldLabel.customer_cf2)}
                    <td class="td_sep"></td>
                    <th class="details_screen">{$customFieldLabel.customer_cf2}:</th>
                    <td>{$customer.custom_field2|htmlSafe}</td>
                {else}
                    <td colspan="3"></td>
                {/if}
            </tr>
            <tr>
                <th class="details_screen">{$LANG.enabled}:</th>
                <td>{$customer.enabled_text|htmlSafe}</td>
                {if !empty($customFieldLabel.customer_cf3)}
                    <td class="td_sep"></td>
                    <th class="details_screen">{$customFieldLabel.customer_cf3}:</th>
                    <td>{$customer.custom_field3|htmlSafe}</td>
                {else}
                    <td colspan="3"></td>
                {/if}
            </tr>
            <tr>
                {if !empty($customFieldLabel.customer_cf4)}
                    <th class="details_screen">{$customFieldLabel.customer_cf4}:</th>
                    <td>{$customer.custom_field4|htmlSafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
                <td class="td_sep"></td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>
</div>
<div class="si_form" id="si_form_cust">
    <div id="tabs_customer">
        <ul class="anchors">
            <li><a href="#section-1" target="_top">{$LANG.summary_of_accounts}</a></li>
            <li><a href="#section-2" target="_top">{$LANG.credit_card_details}</a></li>
            <li><a href="#section-3" target="_top">{$LANG.customer}&nbsp;{$LANG.invoice_listings}</a></li>
            <li {if $invoices_owing_count == 0}style="display:none"{/if}><a href="#section-4" target="_top">{$LANG.unpaid_invoices}</a></li>
            <li><a href="#section-5" target="_top">{$LANG.notes}</a></li>
            <li {if $subCustomerCount == 0}style="display:none"{/if}><a href="#section-6" target="_top">{$LANG.sub_customers}</a></li>
        </ul>
        <div id="section-1" class="fragment">
            <div class="si_cust_account">
                <table class="si_center" style="width:100%;">
                    <tr>
                        <th class="details_screen">{$LANG.total_invoices}:</th>
                        <td class="si_right">{$customer.total|utilCurrency}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">
                            <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                                {$LANG.total_paid}
                            </a>
                            :
                        </th>
                        <td class="si_right">{$customer.paid|utilCurrency}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">{$LANG.total_owing}:</th>
                        <td class="si_right" style="text-decoration:underline;">{$customer.owing|utilCurrency}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="section-2" class="fragment">
            <div class="si_cust_card">
                <table>
                    <tr>
                        <th class="details_screen">{$LANG.credit_card_holder_name}:</th>
                        <td>{$customer.credit_card_holder_name|htmlSafe}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">{$LANG.credit_card_number}:</th>
                        <td>{$customer.credit_card_number_masked|htmlSafe}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">{$LANG.credit_card_expiry_month}:</th>
                        <td>{$customer.credit_card_expiry_month|htmlSafe}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">{$LANG.credit_card_expiry_year}:</th>
                        <td>{$customer.credit_card_expiry_year|htmlSafe}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="section-3" class="fragment">
            <div class="si_cust_invoices">
                <table>
                    <thead>
                    <tr class="tr_head">
                        <th class="first">{$LANG.invoice_uc}</th>
                        <th class="details_screen">{$LANG.date_created}</th>
                        <th class="details_screen">{$LANG.total}</th>
                        <th class="details_screen">{$LANG.paid}</th>
                        <th class="details_screen">{$LANG.owing_uc}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $invoices as $invoice}
                        <tr class="index_table">
                            <td class="first">
                                <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                    {$invoice.index_name|htmlSafe}
                                </a>
                            </td>
                            <td class="si_center">{$invoice.date|htmlSafe}</td>
                            <td class="right">{$invoice.total|utilCurrency}</td>
                            <td class="right">{$invoice.paid|utilCurrency}</td>
                            <td class="right">{$invoice.owing|utilCurrency}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if $invoices_owing_count != 0}
            <div id="section-4" class="fragment">
                <div class="si_cust_invoices">
                    <table>
                        <thead>
                        <tr class="tr_head">
                            <th class="first">{$LANG.actions}</th>
                            <th class="details_screen">{$LANG.id}</th>
                            <th class="details_screen">{$LANG.date_created}</th>
                            <th class="details_screen">{$LANG.total}</th>
                            <th class="details_screen">{$LANG.paid}</th>
                            <th class="details_screen">{$LANG.owing_uc}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $invoices_owing as $invoice}
                            <tr class="index_table">
                                <td class="first">
                                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                        <img src='../../../../../images/view.png' class='action' alt=""/>
                                    </a>
                                    <a title="{$LANG.process_payment_for} {$invoice.preference} {$invoice.index_id}"
                                       href='index.php?module=payments&amp;view=process&amp;id={$invoice.id}&amp;op=pay_selected_invoice'>
                                        <img src='../../../../../images/money_dollar.png' class='action' alt=""/>
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                        {$invoice.index_name|htmlSafe}
                                    </a>
                                </td>
                                <td class="si_center">{$invoice.date|htmlSafe}</td>
                                <td class="right">{$invoice.total|utilCurrency}</td>
                                <td class="right">{$invoice.paid|utilCurrency}</td>
                                <td class="right">{$invoice.owing|utilCurrency}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}
        <div id="section-5" class="fragment">
            <div class="si_cust_notes">{$customer.notes|outHtml}</div>
        </div>
        {if $subCustomerCount != 0}
            <div id="section-6" class="fragment">
                <div class="si_cust_invoices">
                    <table>
                        <thead>
                        <tr class="tr_head">
                            <th class="sortable"></th>
                            <th class="sortable">{$LANG.name}</th>
                        </tr>
                        {foreach $subCustomers as $sc}
                            <tr class="index_table">
                                <td>
                                    <a href="index.php?module=customers&amp;view=edit&amp;id={$sc.id|urlencode}">{$LANG.edit|htmlSafe}</a>
                                    ::
                                    <a href="index.php?module=customers&amp;view=view&amp;id={$sc.id|urlencode}">{$LANG.view|htmlSafe}</a>
                                </td>
                                <td style="text-align:left">{$sc.name|htmlSafe}</td>
                            </tr>
                        {/foreach}
                    </table>
                </div>
            </div>
        {/if}
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=customers&amp;view=edit&amp;id={$customer.id|urlencode}" class="positive">
            <img src="../../../../../images/tick.png" alt="{$LANG.edit}"/>
            {$LANG.edit}
        </a>
        <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="negative">
            <img src="../../../../../images/cross.png" alt="{$LANG.cancel}"/>
            {$LANG.cancel}
        </a>
    </div>
</div>
