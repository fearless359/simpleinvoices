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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}">
    <div class="si_form" id="si_form_cust_edit">
        <table class="center" style="width:90%;">
            <tr>
                <th class="details_screen" tabindex="-1">{$LANG.customerName}:
                    <a class="cluetip" tabindex="-1" href="#" title="{$LANG.requiredField}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" id="name" name="name" value="{if isset($customer.name)}{$customer.name|htmlSafe}{/if}" size="50"
                           class="validate[required]" tabindex="10"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customerDepartment}:</th>
                <td>
                    <input type="text" id="department" name="department" value="{if isset($customer.department)}{$customer.department|htmlSafe}{/if}" size="50"
                           tabindex="15"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.attention}:
                    <a rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerContact"
                       href="#" class="cluetip" title="{$LANG.customerContact}" tabindex="-1">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="attention" value="{if isset($customer.attention)}{$customer.attention|htmlSafe}{/if}" size="50" tabindex="20"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.street}:</th>
                <td>
                    <input type="text" name="street_address" value="{if isset($customer.street_address)}{$customer.street_address|htmlSafe}{/if}" size="50" tabindex="30"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.street2}:
                    <a class="cluetip" href="#" title="{$LANG.street2}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="street_address2" value="{if isset($customer.street_address2)}{$customer.street_address2|htmlSafe}{/if}"
                           size="50" tabindex="40"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.city}:</th>
                <td>
                    <input type="text" name="city" value="{if isset($customer.city)}{$customer.city|htmlSafe}{/if}" size="50" tabindex="50"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.zip}:</th>
                <td>
                    <input type="text" name="zip_code" value="{if isset($customer.zip_code)}{$customer.zip_code|htmlSafe}{/if}" size="50" tabindex="60"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.state}:</th>
                <td>
                    <input type="text" name="state" value="{if isset($customer.state)}{$customer.state|htmlSafe}{/if}" size="50" tabindex="70"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.country}:</th>
                <td>
                    <input type="text" name="country" value="{if isset($customer.country)}{$customer.country|htmlSafe}{/if}" size="50" tabindex="80"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.phoneUc}:</th>
                <td>
                    <input type="text" name="phone" value="{if isset($customer.phone)}{$customer.phone|htmlSafe}{/if}" size="50" tabindex="90"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.mobilePhone}:</th>
                <td>
                    <input type="text" name="mobile_phone" value="{if isset($customer.mobile_phone)}{$customer.mobile_phone|htmlSafe}{/if}"
                           size="50" tabindex="100"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.fax}:</th>
                <td>
                    <input type="text" name="fax" value="{if isset($customer.fax)}{$customer.fax|htmlSafe}{/if}" size="50" tabindex="110"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.email}:
                    <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.email}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerEmail">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="email" class="validate[required,custom[email]] text-input"
                           value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}" size="50" tabindex="120"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.creditCardHolderName}:</th>
                <td>
                    <input type="text" name="credit_card_holder_name"
                           value="{if isset($customer.credit_card_holder_name)}{$customer.credit_card_holder_name|htmlSafe}{/if}" size="25" tabindex="130"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.creditCardNumber}:</th>
                <td>{$customer.credit_card_number_masked}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.creditCardNumberNew}:</th>
                <td>
                    {* Note that no value is put in this field and the name is the actual database name *}
                    <input type="text" name="credit_card_number" size="25" tabindex="140"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.creditCardExpiryMonth}:</th>
                <td>
                    <input type="text" name="credit_card_expiry_month"
                           value="{if isset($customer.credit_card_expiry_month)}{$customer.credit_card_expiry_month|htmlSafe}{/if}" size="5" tabindex="150"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.creditCardExpiryYear}:</th>
                <td>
                    <input type="text" name="credit_card_expiry_year"
                           value="{if isset($customer.credit_card_expiry_year)}{$customer.credit_card_expiry_year|htmlSafe}{/if}" size="5" tabindex="160"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.defaultInvoice}:
                    <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultInvoice}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultInvoice">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select class="si_input" name="default_invoice" tabindex="165">
                        <option value="0"></option>
                        {foreach $invoices as $invoice}
                            <option {if $invoice.index_id == $customer.default_invoice}selected{/if}
                                    value="{$invoice.index_id}">{$invoice.index_name} ({$invoice.total|utilCurrency})
                            </option>
                        {/foreach}
                    </select>

                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.parentCustomer}</th>
                <td>
                    {if !isset($parent_customers) }
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <select name="parent_customer_id" class="si_input" tabindex="168">
                            <option value=''></option>
                            {foreach $parent_customers as $c}
                                <option {if $c.id == $customer.parent_customer_id}selected{/if} value="{if isset($c.id)}{$c.id|htmlSafe}{/if}">{$c.name|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
            </tr>
            {if !empty($customFieldLabel.customer_cf1)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.customer_cf1|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input class="si_input" type="text" name="custom_field1" size="50" tabindex="170"
                               value="{if isset($customer.custom_field1)}{$customer.custom_field1|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.customer_cf2)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.customer_cf2|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input class="si_input" type="text" name="custom_field2" size="50" tabindex="180"
                               value="{if isset($customer.custom_field2)}{$customer.custom_field2|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.customer_cf3)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.customer_cf3|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input class="si_input" type="text" name="custom_field3" size="50" tabindex="190"
                               value="{if isset($customer.custom_field3)}{$customer.custom_field3|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.customer_cf4)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.customer_cf4|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input class="si_input" type="text" name="custom_field4" size="50" tabindex="200"
                               value="{if isset($customer.custom_field4)}{$customer.custom_field4|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.notes}:</th>
                <td>
                    <input name="notes" id="notes" {if isset($customer.notes)}value="{$customer.notes|outHtml}"{/if} type="hidden">
                    <trix-editor class="si_input" input="notes" tabindex="210"></trix-editor>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.enabled}:</th>
                <td>{html_options class=si_input name=enabled options=$enabled selected=$customer.enabled tabindex=220}</td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_customer" value="{$LANG.saveCustomer}" tabindex="230">
                <img class="button_img" src="../../../../../images/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="negative">
                <img src="../../../../../images/cross.png" alt="{$LANG.cancel}"/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="domain_id" value="{if isset($customer.domain_id)}{$customer.domain_id}{/if}"/>
</form>
