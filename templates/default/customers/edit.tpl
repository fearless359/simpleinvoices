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
{if !empty($errorMsg)}
    <h3 class="align__text-center si_message_error">{$errorMsg}</h3>
{/if}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="name" class="cols__2-span-3" tabindex="-1">{$LANG.customerName}:
                <a class="cluetip" tabindex="-1" href="#" title="{$LANG.requiredField}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                    <img src="{$helpImagePath}required-small.png" alt=""/>
                </a>
            </label>
            <input type="text" id="name" name="name" value="{if isset($customer.name)}{$customer.name|htmlSafe}{/if}"
                   class="cols__5-span-6 validate[required]" autofocus tabindex="10"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="department" class="cols__2-span-3">{$LANG.customerDepartment}:</label>
            <input type="text" name="department" id="department" class="cols__5-span-6" tabindex="15"
                   value="{if isset($customer.department)}{$customer.department|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="attentionId" class="cols__2-span-3">{$LANG.attention}:
                <a rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerContact"
                   href="#" class="cluetip" title="{$LANG.customerContact}" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="attention" id="attentionId" class="cols__5-span-6" tabindex="20"
                   value="{if isset($customer.attention)}{$customer.attention|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddressId" class="cols__2-span-3">{$LANG.street}:</label>
            <input type="text" name="street_address" id="streetAddressId" class="cols__5-span-6" tabindex="30"
                   value="{if isset($customer.street_address)}{$customer.street_address|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddress2Id" class="cols__2-span-3">{$LANG.street2}:
                <a class="cluetip" href="#" title="{$LANG.street2}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="street_address2" id="streetAddress2Id" class="cols__5-span-6" tabindex="40"
                   value="{if isset($customer.street_address2)}{$customer.street_address2|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="cityId" class="cols__2-span-3">{$LANG.city}:</label>
            <input type="text" name="city" id="cityId" class="cols__5-span-6" tabindex="50"
                   value="{if isset($customer.city)}{$customer.city|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="stateId" class="cols__2-span-3">{$LANG.state}:</label>
            <input type="text" name="state" id="stateId" class="cols__5-span-6" tabindex="70"
                   value="{if isset($customer.state)}{$customer.state|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="zipCodeId" class="cols__2-span-3">{$LANG.zip}:</label>
            <input type="text" name="zip_code" id="zipCodeId" class="cols__5-span-6" tabindex="60"
                   value="{if isset($customer.zip_code)}{$customer.zip_code|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="countryId" class="cols__2-span-3">{$LANG.country}:</label>
            <input type="text" name="country" id="countryId" class="cols__5-span-6" tabindex="80"
                   value="{if isset($customer.country)}{$customer.country|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="phoneId" class="cols__2-span-3">{$LANG.phoneUc}:</label>
            <input type="text" name="phone" id="phoneId" class="cols__5-span-6" tabindex="90"
                   value="{if isset($customer.phone)}{$customer.phone|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="mobilePhoneId" class="cols__2-span-3">{$LANG.mobilePhone}:</label>
            <input type="text" name="mobile_phone" id="mobilePhoneId" class="cols__5-span-6" tabindex="100"
                   value="{if isset($customer.mobile_phone)}{$customer.mobile_phone|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="faxId" class="cols__2-span-3">{$LANG.fax}:</label>
            <input type="text" name="fax" id="faxId" class="cols__5-span-6" tabindex="110"
                   value="{if isset($customer.fax)}{$customer.fax|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailId" class="cols__2-span-3">{$LANG.email}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.email}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerEmail">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="email" id="emailId" class="cols__5-span-6 validate[required,custom[email]] text-input"
                   value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}" tabindex="120"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="ccName" class="cols__2-span-3">{$LANG.creditCardHolderName}:
                <a class="cluetip" href="#" title="{$LANG.creditCardHolderName}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpCreditCardHolderName">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="credit_card_holder_name" id="ccName" tabindex="130"
                   class="cols__5-span-6 margin__left-0-5 creditCard validate[condRequired[ccNumber,ccExpMonth,ccExpYear]]"
                   value="{if isset($customer.credit_card_holder_name)}{$customer.credit_card_holder_name|htmlSafe}{/if}"/>
        </div>
        {if !empty($customer.credit_card_number_masked)}
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-3 bold">{$LANG.creditCardNumber}:</div>
                <div class="cols__5-span-6">{$customer.credit_card_number_masked}</div>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="ccNumber" class="cols__2-span-3">{$LANG.creditCardNumberNew}:
                <a class="cluetip" href="#" title="{$LANG.creditCardNew}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpCreditCardNumber">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="credit_card_number" id="ccNumber" tabindex="140"
                   class="cols__5-span-6 margin__left-0-5 creditCard"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="ccExpMonth" class="cols__2-span-3">{$LANG.creditCardExpiryMonth}:</label>
            <select name="credit_card_expiry_month" id="ccExpMonth" tabindex="150"
                    class="cols__5-span-1 creditCard validate[condRequired[ccName, ccNumber, ccExpYear]]">
                <option></option>
                {for $mon=1 to 12}
                    <option value="{$mon}"
                            {if isset($customer.credit_card_expiry_month) &&
                            $customer.credit_card_expiry_month==$mon}selected{/if}>{$mon|string_format:"%02d"}</option>
                {/for}
            </select>
            <label for="ccExpYear" class="cols__7-span-3">{$LANG.creditCardExpiryYear}:</label>
            <select name="credit_card_expiry_year" id="ccExpYear" tabindex="160"
                    class="cols__10-span-1 creditCard validate[condRequired[ccName, ccNumber, ccExpiryMonth]]">
                <option></option>
                {for $yr=21 to 30}
                    <option value="{$yr}"
                            {if isset($customer.credit_card_expiry_year) &&
                            $customer.credit_card_expiry_year==$yr}selected{/if}>{$yr|string_format:"20%02d"}</option>
                {/for}
            </select>
        </div>

        {if $invoiceCount > 0}
            <div class="grid__container grid__head-10">
                <label for="defaultInvoiceId" class="cols__2-span-3">{$LANG.defaultInvoice}:
                    <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultInvoice}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultInvoice">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <select name="default_invoice" id="defaultInvoiceId" class="cols__5-span-6 margin__left-0-5" tabindex="165">
                    <option value="0"></option>
                    {foreach $invoices as $invoice}
                        <option {if $invoice.index_id == $customer.default_invoice}selected{/if}
                                value="{$invoice.index_id}">{$invoice.index_name} ({$invoice.total|utilCurrency})
                        </option>
                    {/foreach}
                </select>
            </div>
        {/if}
        {if $subCustomerEnabled}
            {if $isParent}
                <div class="grid__container grid__head-10">
                    <div class="cols__2-span-3 bold">{$LANG.parentOfCustomers}:
                        <a class="cluetip" href="#" title="{$LANG.parentOfCustomers}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpParentOfCustomers">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </div>
                    <div class="cols__5-span-6">
                        <ul>
                            {foreach $childCustomers as $cc}
                                <li><a href="index.php?module=customers&amp;view=view&amp;id={$cc.id}">{$cc.name|htmlSafe}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            {else}
                <div class="grid__container grid__head-10">
                    <label for="parentCustomerId" class="cols__2-span-3">{$LANG.parentCustomer}:
                        <a class="cluetip" href="#" title="{$LANG.parentCustomer}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpParentCustomer">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    {if !isset($parentCustomers) }
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <select name="parent_customer_id" id="parentCustomerId" tabindex="168"
                                class="cols__5-span-6 margin__left-0-5">
                            <option value="0"></option>
                            {foreach $parentCustomers as $pc}
                                <option {if $pc.id == $customer.parent_customer_id}selected{/if}
                                        value="{if isset($pc.id)}{$pc.id|htmlSafe}{/if}">{$pc.name|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
            {/if}
        {/if}
        {if !empty($customFieldLabel.customer_cf1)}
            <div class="grid__container grid__head-10">
                <label for="customField1" class="cols__2-span-3">{$customFieldLabel.customer_cf1|htmlSafe}:
                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="custom_field1" id="customField1" class="cols__5-span-6 margin__left-0-5" tabindex="170"
                       value="{if isset($customer.custom_field1)}{$customer.custom_field1|htmlSafe}{/if}"/>
            </div>
        {/if}
        {if !empty($customFieldLabel.customer_cf2)}
            <div class="grid__container grid__head-10">
                <label for="customField2" class="cols__2-span-3">{$customFieldLabel.customer_cf2|htmlSafe}:
                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="custom_field2" id="customField2" class="cols__5-span-6 margin__left-0-5" tabindex="180"
                       value="{if isset($customer.custom_field2)}{$customer.custom_field2|htmlSafe}{/if}"/>
            </div>
        {/if}
        {if !empty($customFieldLabel.customer_cf3)}
            <div class="grid__container grid__head-10">
                <label for="customField3" class="cols__2-span-3">{$customFieldLabel.customer_cf3|htmlSafe}:
                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="custom_field3" id="customField3" class="cols__5-span-6 margin__left-0-5" tabindex="190"
                       value="{if isset($customer.custom_field3)}{$customer.custom_field3|htmlSafe}{/if}"/>
            </div>
        {/if}
        {if !empty($customFieldLabel.customer_cf4)}
            <div class="grid__container grid__head-10">
                <label for="customField4" class="cols__2-span-3">{$customFieldLabel.customer_cf4|htmlSafe}:
                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="custom_field4" id="customField4" class="cols__5-span-6 margin__left-0-5" tabindex="200"
                       value="{if isset($customer.custom_field4)}{$customer.custom_field4|htmlSafe}{/if}"/>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="" class="cols__2-span-3">{$LANG.notes}:</label>
            <div class="cols__2-span-9">
                <input name="notes" id="notes" {if isset($customer.notes)}value="{$customer.notes|outHtml}"{/if} type="hidden">
                <trix-editor input="notes" tabindex="210"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__2-span-3">{$LANG.enabled}:</label>
            {html_options name=enabled id=enabledId class=cols__5-span-6 options=$enabled selected=$customer.enabled tabindex=220}
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_customer" value="{$LANG.saveCustomer}" tabindex="230">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=customers&amp;view=manage" tabindex="-1" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="domain_id" value="{if isset($customer.domain_id)}{$customer.domain_id}{/if}"/>
    <input type="hidden" name="origCcMaskedValue" value="{$customer.credit_card_number_masked}"/>
</form>
