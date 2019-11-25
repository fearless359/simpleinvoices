{*
 * Script: details.tpl
 *      Customer details template
 *
 * Last modified:
 *      2016-07-27
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}
{if $smarty.get.action == 'view' }
    <br/>
    <div class="si_form si_form_view" id="si_form_cust">
        <div class="si_cust_info">
            <table>
                <tr>
                    <th>{$LANG.customer_name}: </th>
                    <td>{$customer.name}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.customer_department}: </th>
                    <td>{$customer.department|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.attention_short}: </th>
                    <td>{$customer.attention|htmlsafe}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.phone}: </th>
                    <td>{$customer.phone|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.street}: </th>
                    <td>{$customer.street_address|htmlsafe}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.mobile_phone}: </th>
                    <td>{$customer.mobile_phone|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.street2}: </th>
                    <td>{$customer.street_address2|htmlsafe}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.fax}: </th>
                    <td>{$customer.fax|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.city}: </th>
                    <td>{$customer.city|htmlsafe}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.email}: </th>
                    <td><a href="mailto:{$customer.email|htmlsafe}">{$customer.email|htmlsafe}</a></td>
                </tr>
                <tr>
                    <th>{$LANG.zip}: </th>
                    <td>{$customer.zip_code|htmlsafe}</td>
                    <td class="td_sep"></td>
                    <th>{$LANG.default_invoice}: </th>
                    <td>{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</td>
                </tr>
                <tr>
                    <th>{$LANG.state}: </th>
                    <td>{$customer.state|htmlsafe}</td>
                    {if !empty($customFieldLabel.customer_cf1)}
                        <td class="td_sep"></td>
                        <th>{$customFieldLabel.customer_cf1}: </th>
                        <td>{$customer.custom_field1|htmlsafe}</td>
                    {else}
                        <td colspan="3"></td>
                    {/if}
                </tr>
                <tr>
                    <th>{$LANG.country}: </th>
                    <td>{$customer.country|htmlsafe}</td>
                    {if !empty($customFieldLabel.customer_cf2)}
                        <td class="td_sep"></td>
                        <th>{$customFieldLabel.customer_cf2}: </th>
                        <td>{$customer.custom_field2|htmlsafe}</td>
                    {else}
                        <td colspan="3"></td>
                    {/if}
                </tr>
                <tr>
                    <th>{$LANG.enabled}: </th>
                    <td>{$customer.enabled_text|htmlsafe}</td>
                    {if !empty($customFieldLabel.customer_cf3)}
                        <td class="td_sep"></td>
                        <th>{$customFieldLabel.customer_cf3}: </th>
                        <td>{$customer.custom_field3|htmlsafe}</td>
                    {else}
                        <td colspan="3"></td>
                    {/if}
                </tr>
                <tr>
                    {if !empty($customFieldLabel.customer_cf4)}
                        <th>{$customFieldLabel.customer_cf4}: </th>
                        <td>{$customer.custom_field4|htmlsafe}</td>
                    {else}
                        <td colspan="2"></td>
                    {/if}
                    <td class="td_sep"></td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="si_form si_form_view" id="si_form_cust">
        <div id="tabs_customer">
            <ul class="anchors">
                <li><a href="#section-1" target="_top">{$LANG.summary_of_accounts}</a></li>
                <li><a href="#section-2" target="_top">{$LANG.credit_card_details}</a></li>
                <li><a href="#section-3" target="_top">{$LANG.customer}&nbsp;{$LANG.invoice_listings}</a></li>
                <li {if $invoices_owing_count == 0}style="display:none"{/if}><a href="#section-4" target="_top">{$LANG.unpaid_invoices}</a></li>
                <li><a href="#section-5" target="_top">{$LANG.notes}</a></li>
            </ul>
            <div id="section-1" class="fragment">
                <div class="si_cust_account">
                    <table>
                        <tr>
                            <th>{$LANG.total_invoices}: </th>
                            <td class="si_right">{$customer.total|siLocal_number}</td>
                        </tr>
                        <tr>
                            <th>
                                <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                                    {$LANG.total_paid}
                                </a>
                            : </th>
                            <td class="si_right">{$customer.paid|siLocal_number}</td>
                        </tr>
                        <tr>
                            <th>{$LANG.total_owing}: </th>
                            <td class="si_right" style="text_decoration:underline;">{$customer.owing|siLocal_number}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="section-2" class="fragment">
                <div class="si_cust_card">
                    <table>
                        <tr>
                            <th>{$LANG.credit_card_holder_name}: </th>
                            <td>{$customer.credit_card_holder_name|htmlsafe}</td>
                        </tr>
                        <tr>
                            <th>{$LANG.credit_card_number}: </th>
                            <td>{$customer.credit_card_number_masked|htmlsafe}</td>
                        </tr>
                        <tr>
                            <th>{$LANG.credit_card_expiry_month}: </th>
                            <td>{$customer.credit_card_expiry_month|htmlsafe}</td>
                        </tr>
                        <tr>
                            <th>{$LANG.credit_card_expiry_year}: </th>
                            <td>{$customer.credit_card_expiry_year|htmlsafe}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="section-3" class="fragment">
                <div class="si_cust_invoices">
                    <table>
                        <thead>
                        <tr class="tr_head">
                            <th class="first">{$LANG.invoice}</th>
                            <th>{$LANG.date_created}</th>
                            <th>{$LANG.total}</th>
                            <th>{$LANG.paid}</th>
                            <th>{$LANG.owing}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $invoices as $invoice}
                            <tr class="index_table">
                                <td class="first">
                                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                        {$invoice.index_name|htmlsafe}
                                    </a>
                                </td>
                                <td class="si_center">{$invoice.date|htmlsafe}</td>
                                <td class="right">{$invoice.total|siLocal_currency}</td>
                                <td class="right">{$invoice.paid|siLocal_currency}</td>
                                <td class="right">{$invoice.owing|siLocal_currency}</td>
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
                            <th>{$LANG.id}</th>
                            <th>{$LANG.date_created}</th>
                            <th>{$LANG.total}</th>
                            <th>{$LANG.paid}</th>
                            <th>{$LANG.owing}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $invoices_owing as $invoice}
                            <tr class="index_table">
                                <td class="first">
                                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                        <img src='images/common/view.png' class='action'/>
                                    </a>
                                    <a title="{$LANG.process_payment_for} {$invoice.preference} {$invoice.index_id}"
                                       href='index.php?module=payments&amp;view=process&amp;id={$invoice.id}&amp;op=pay_selected_invoice'>
                                        <img src='images/common/money_dollar.png' class='action'/>
                                    </a>
                                </td>
                                <td>
                                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice.id|urlencode}">
                                        {$invoice.index_name|htmlsafe}
                                    </a>
                                </td>
                                <td class="si_center">{$invoice.date|htmlsafe}</td>
                                <td class="right">{$invoice.total|siLocal_currency}</td>
                                <td class="right">{$invoice.paid|siLocal_currency}</td>
                                <td class="right">{$invoice.owing|siLocal_currency}</td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
            {/if}
            <div id="section-5" class="fragment">
                <div class="si_cust_notes">{$customer.notes|outhtml}</div>
            </div>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="positive">
                <img src="images/common/tick.png" alt="{$LANG.edit}"/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="negative">
                <img src="images/common/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
            </a>
        </div>
    </div>
{elseif $smarty.get.action == 'edit' }
        <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}">
        <div class="si_form" id="si_form_cust_edit">
            <table class="center">
                <tr>
                    <th>{$LANG.customer_name}
                        <a class="cluetip" href="#" title="{$LANG.required_field}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field">
                            <img src="{$help_image_path}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="name" value="{if isset($customer.name)}{$customer.name|htmlsafe}{/if}" size="50" id="name"
                               class="validate[required]" tabindex="10"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.customer_department}</th>
                    <td>
                        <input type="text" name="department" value="{if isset($customer.department)}{$customer.department|htmlsafe}{/if}" size="50" id="department"
                               tabindex="15"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.attention_short}
                        <a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
                           href="#" class="cluetip" title="{$LANG.customer_contact}">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="attention" value="{if isset($customer.attention)}{$customer.attention|htmlsafe}{/if}" size="50" tabindex="20"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street}</th>
                    <td>
                        <input type="text" name="street_address" value="{if isset($customer.street_address)}{$customer.street_address|htmlsafe}{/if}" size="50" tabindex="30"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street2}
                        <a class="cluetip" href="#" title="{$LANG.street2}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_street2">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="street_address2" value="{if isset($customer.street_address2)}{$customer.street_address2|htmlsafe}{/if}"
                               size="50" tabindex="40"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.city}</th>
                    <td>
                        <input type="text" name="city" value="{if isset($customer.city)}{$customer.city|htmlsafe}{/if}" size="50" tabindex="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.zip}</th>
                    <td>
                        <input type="text" name="zip_code" value="{if isset($customer.zip_code)}{$customer.zip_code|htmlsafe}{/if}" size="50" tabindex="60"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.state}</th>
                    <td>
                        <input type="text" name="state" value="{if isset($customer.state)}{$customer.state|htmlsafe}{/if}" size="50" tabindex="70"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.country}</th>
                    <td>
                        <input type="text" name="country" value="{if isset($customer.country)}{$customer.country|htmlsafe}{/if}" size="50" tabindex="80"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.phone}</th>
                    <td>
                        <input type="text" name="phone" value="{if isset($customer.phone)}{$customer.phone|htmlsafe}{/if}" size="50" tabindex="90"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.mobile_phone}</th>
                    <td>
                        <input type="text" name="mobile_phone" value="{if isset($customer.mobile_phone)}{$customer.mobile_phone|htmlsafe}{/if}"
                               size="50" tabindex="100"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.fax}</th>
                    <td>
                        <input type="text" name="fax" value="{if isset($customer.fax)}{$customer.fax|htmlsafe}{/if}" size="50" tabindex="110"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.email}
                        <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.email}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_email">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="email" class="validate[required,custom[email]] text-input"
                               value="{if isset($customer.email)}{$customer.email|htmlsafe}{/if}" size="50" tabindex="120"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_holder_name}</th>
                    <td>
                        <input type="text" name="credit_card_holder_name"
                               value="{if isset($customer.credit_card_holder_name)}{$customer.credit_card_holder_name|htmlsafe}{/if}" size="25" tabindex="130"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_number}</th>
                    <td>{$customer.credit_card_number_masked}</td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_number_new}</th>
                    <td>
                        {* Note that no value is put in this field and the name is the actual database name *}
                        <input type="text" name="credit_card_number" size="25" tabindex="140"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_expiry_month}</th>
                    <td>
                        <input type="text" name="credit_card_expiry_month"
                               value="{if isset($customer.credit_card_expiry_month)}{$customer.credit_card_expiry_month|htmlsafe}{/if}" size="5" tabindex="150"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_expiry_year}</th>
                    <td>
                        <input type="text" name="credit_card_expiry_year"
                               value="{if isset($customer.credit_card_expiry_year)}{$customer.credit_card_expiry_year|htmlsafe}{/if}" size="5" tabindex="160"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.default_invoice}
                        <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_invoice}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        {*<input type="text" name="default_invoice"*}
                               {*value="{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}" size="8" tabindex="165"/>*}
                        <select name="default_invoice" tabindex="165">
                            <option value="0"></option>
                            {foreach $invoices as $invoice}
                                <option {if $invoice.index_id == $customer.default_invoice}selected{/if}
                                        value="{$invoice.index_id}">Inv#{$invoice.index_id} ({$invoice.name|htmlsafe} {$invoice.total|siLocal_number})</option>
                            {/foreach}
                        </select>

                    </td>
                </tr>
                {if !empty($customFieldLabel.customer_cf1)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf1|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" name="custom_field1"
                                   value="{if isset($customer.custom_field1)}{$customer.custom_field1|htmlsafe}{/if}" size="50" tabindex="170"/>
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf2)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf2|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" name="custom_field2"
                                   value="{if isset($customer.custom_field2)}{$customer.custom_field2|htmlsafe}{/if}" size="50" tabindex="180"/>
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf3)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf3|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" name="custom_field3"
                                   value="{if isset($customer.custom_field3)}{$customer.custom_field3|htmlsafe}{/if}" size="50" tabindex="190"/>
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf4)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf4|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" name="custom_field4"
                                   value="{if isset($customer.custom_field4)}{$customer.custom_field4|htmlsafe}{/if}" size="50" tabindex="200"/>
                        </td>
                    </tr>
                {/if}
                <tr>
                    <th>{$LANG.notes}</th>
                    <td>
                        <!--
                        <textarea name="notes" class="editor" rows="6" cols="80" tabindex="210">{*$customer.notes|outhtml*}</textarea>
                        -->
                        <input name="notes" id="notes" {if isset($customer.notes)}value="{$customer.notes|outhtml}"{/if} type="hidden">
                        <trix-editor input="notes" tabindex="210"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>{html_options name=enabled options=$enabled selected=$customer.enabled tabindex=220}</td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_customer" value="{$LANG.save_customer}" tabindex="230">
                    <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="negative">
                    <img src="images/common/cross.png" alt="{$LANG.cancel}" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="domain_id" value="{if isset($customer.domain_id)}{$customer.domain_id}{/if}"/>
    </form>
{/if}
