{*
 * Script: details.tpl
 *      Customer details template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area delay__display">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.customerName}:</div>
        <div class="cols__4-span-6">{$customer.name}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.customerDepartment}:</div>
        <div class="cols__4-span-6">{$customer.department|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.attentionShort}:</div>
        <div class="cols__4-span-6">{$customer.attention|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.street}:</div>
        <div class="cols__4-span-6">{$customer.street_address|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.street2}:</div>
        <div class="cols__4-span-6">{$customer.street_address2|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.city}:</div>
        <div class="cols__4-span-6">{$customer.city|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.state}:</div>
        <div class="cols__4-span-6">{$customer.state|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.zip}:</div>
        <div class="cols__4-span-6">{$customer.zip_code|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.country}:</div>
        <div class="cols__4-span-6">{$customer.country|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.phoneUc}:</div>
        <div class="cols__4-span-6">{$customer.phone|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.mobilePhone}:</div>
        <div class="cols__4-span-6">{$customer.mobile_phone|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.fax}:</div>
        <div class="cols__4-span-6">{$customer.fax|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.email}:</div>
        <div class="cols__4-span-6"><a href="mailto:{$customer.email|htmlSafe}">{$customer.email|htmlSafe}</a></div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.defaultInvoice}:</div>
        <div class="cols__4-span-6">{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</div>
    </div>
    {if !empty($customFieldLabel.customer_cf1)}
    <div class="grid__container grid__head-10">
            <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.customer_cf1}:</div>
            <div class="cols__4-span-6">{$customer.custom_field1|htmlSafe}</div>
    </div>
    {/if}
    {if !empty($customFieldLabel.customer_cf2)}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.customer_cf2}:</div>
        <div class="cols__4-span-6">{$customer.custom_field2|htmlSafe}</div>
    </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.enabled}:</div>
        <div class="cols__4-span-6">{$customer.enabled_text|htmlSafe}</div>
    </div>
    {if !empty($customFieldLabel.customer_cf3)}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.customer_cf3}:</div>
        <div class="cols__4-span-6">{$customer.custom_field3|htmlSafe}</div>
    </div>
    {/if}
    {if !empty($customFieldLabel.customer_cf4)}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabel.customer_cf4}:</div>
        <div class="cols__3-span-3">{$customer.custom_field4|htmlSafe}</div>
    </div>
    {/if}
    <div class="si_form" id="si_form_cust">
        <div id="tabs_customer">
            <ul class="anchors">
                <li><a href="#section-1" target="_top">{$LANG.summaryOfAccounts}</a></li>
                <li><a href="#section-2" target="_top">{$LANG.creditCardDetails}</a></li>
                <li><a href="#section-3" target="_top">{$LANG.customerUc}&nbsp;{$LANG.invoicesUc}</a></li>
                <li><a href="#section-4" target="_top">{$LANG.notes}</a></li>
                {* Display only if the subCustomer option is enabled. *}
                {if $subCustomerEnabled}
                    {* If this customer has no children, do not display tab *}
                    <li {if empty($childCustomers) && empty($parentCustomer)}style="display:none"{/if}>
                        <a href="#section-5" target="_top">
                            {if !empty($childCustomers)}{$LANG.customersChildren}{else}{$LANG.customersParent}{/if}
                        </a>
                    </li>
                {/if}
            </ul>

            <div id="section-1" class="fragment">
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-1 bold align__text-right margin__right-1">{$LANG.totalInvoices}:</div>
                    <div class="cols__2-span-1 align__text-right">{$customer.total|utilCurrency}</div>
                </div>
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-1 bold align__text-right margin__right-1">
                        <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlEncode}">{$LANG.totalPaid}</a>:
                    </div>
                    <div class="cols__2-span-1 align__text-right underline">{$customer.paid|utilCurrency}</div>
                </div>
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-1 bold align__text-right margin__right-1">{$LANG.totalOwing}:</div>
                    <div class="cols__2-span-1 align__text-right">{$customer.owing|utilCurrency}</div>
                </div>
            </div>

            <div id="section-2" class="fragment">
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.creditCardHolderName}:</div>
                    <div class="cols__3-span-4">{$customer.credit_card_holder_name|htmlSafe}</div>
                </div>
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.creditCardNumber}:</div>
                    <div class="cols__3-span-4">{$customer.credit_card_number_masked|htmlSafe}</div>
                </div>
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.creditCardExpiryMonth}:</div>
                    <div class="cols__3-span-4">
                        {if $customer.credit_card_expiry_month > 0 &&
                        $customer.credit_card_expiry_month < 10}
                            0{$customer.credit_card_expiry_month|htmlSafe}
                        {else}
                            {$customer.credit_card_expiry_month|htmlSafe}
                        {/if}
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.creditCardExpiryYear}:</div>
                    <div class="cols__3-span-4">{$customer.credit_card_expiry_year|htmlSafe}</div>
                </div>
            </div>

            <div id="section-3" class="fragment">
                <table id="custInvoices" class="display responsive compact cell-border">
                    <thead>
                    <tr>
                        <th>{$LANG.invoiceUc}</th>
                        <th>{$LANG.dateCreated}</th>
                        <th>{$LANG.totalUc}</th>
                        <th>{$LANG.paidUc}</th>
                        <th>{$LANG.owingUc}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach $invoices as $invoice}
                        <tr>
                            <td><a href="index.php?module=invoices&amp;view=quickView&amp;id={$invoice.id|urlEncode}">{$invoice.index_id|htmlSafe}</a></td>
                            <td>{$invoice.date|htmlSafe}</td>
                            <td>{$invoice.total|utilCurrency}</td>
                            <td>{$invoice.paid|utilCurrency}</td>
                            <td>{$invoice.owing|utilCurrency}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

            <div id="section-4" class="fragment">
                <div class="si_cust_notes">{$customer.notes|outHtml}</div>
            </div>

            {if $subCustomerEnabled && (!empty($childCustomers) || !empty($parentCustomer))}
                {if !empty($childCustomers)}
                    {$parentChildList = $childCustomers}
                {else}
                    {$parentChildList = $parentCustomer}
                {/if}
                <div id="section-5" class="fragment">
                    <table id="parentChildTable" class="display responsive compact cell-border" style="width:70%;">
                        <colgroup>
                            <col span="1" style="width: 10%">
                            <col span="1" style="width: 45%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{$LANG.actions}</th>
                            <th>{$LANG.nameUc}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $parentChildList as $pcl}
                            <tr>
                                <td>
                                    <a title='{$LANG.view} {$LANG.customerUc} {$pcl.name|htmlSafe}'
                                       href='index.php?module=customers&amp;view=view&amp;id={$pcl.id|urlEncode}'>
                                        <img src='images/view.png' class='action' alt='{$LANG.view} {$LANG.customerUc} {$pcl.name}'/>
                                    </a>
                                    <a class='index_table' title='{$LANG.edit} {$LANG.customerUc} {$pcl.name|htmlSafe}'
                                       href='index.php?module=customers&amp;view=edit&amp;id={$pcl.id|urlEncode}'>
                                        <img src='images/edit.png' class='action' alt='{$LANG.edit} {$LANG.customerUc} {$pcl.name}'/>
                                    </a>
                                </td>
                                <td class="left">{$pcl.name|htmlSafe}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            {/if}
        </div>
    </div>
    <div class="align__text-center margin__bottom-2">
        <a href="index.php?module=customers&amp;view=edit&amp;id={$customer.id|urlEncode}" class="button positive">
            <img src="images/tick.png" alt="{$LANG.edit}"/>{$LANG.edit}
        </a>
        <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
</div>
<script>
    {literal}
    $(document).ready(function() {
        $("div.delay__display").removeClass("delay__display");

        $('#custInvoices').DataTable({
            "responsive": true,
            "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
            "columnDefs": [
                {"targets": 0, "className": 'dt-right' },
                {"targets": 1, "className": 'dt-center' },
                {"targets": 2, "className": "dt-right" },
                {"targets": 3, "className": 'dt-right' },
                {"targets": 4, "className": 'dt-right' }
            ],
            "colReorder": true,
            "order": [
                [0, 'desc'],
                [1, 'asc']
            ]
        });

        $('#parentChildTable').DataTable({
            "responsive": true,
            "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
            "columnDefs": [
                {"targets": 0, "className": 'dt-center' }
            ],
            "colReorder": true,
            "order": [
                [1, 'asc']
            ]
        });

    });
    {/literal}
</script>
