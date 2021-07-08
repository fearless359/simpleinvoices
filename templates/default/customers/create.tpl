{*
 *  Script: create.tpl
 *      New customer template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
*}
{if !empty($smarty.post.name)}
    {* if customer is updated or saved.*}
    {include file="templates/default/customers/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=customers&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="name" class="cols__2-span-3">{$LANG.customerName}:
                    <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="name" id="name" class="cols__5-span-6 validate[required]" tabindex="10" autofocus
                       value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="department" class="cols__2-span-3">{$LANG.customerDepartment}:</label>
                <input type="text" name="department" id="department" class="cols__5-span-6" tabindex="15"
                       value="{if isset($smarty.post.department)}{$smarty.post.department|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="attentionId" class="cols__2-span-3">{$LANG.customerContact}:
                    <a href="#" class="cluetip" title="{$LANG.customerContact}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerContact">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="attention" id="attentionId" class="cols__5-span-6" tabindex="20"
                       value="{if isset($smarty.post.attention)}{$smarty.post.attention|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddressId" class="cols__2-span-3">{$LANG.street}:</label>
                <input type="text" name="street_address" id="streetAddressId" class="cols__5-span-6" tabindex="30"
                       value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddress2Id" class="cols__2-span-3">{$LANG.street2}:
                    <a class="cluetip" href="#" title="{$LANG.street2}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="street_address2" id="streetAddress2Id" class="cols__5-span-6" tabindex="40"
                       value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="cityId" class="cols__2-span-3">{$LANG.city}:</label>
                <input type="text" name="city" id="cityId" class="cols__5-span-3" tabindex="50"
                       value="{if isset($smarty.post.city)}{$smarty.post.city|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="stateId" class="cols__2-span-3">{$LANG.state}:</label>
                <input type="text" name="state" id="stateId" class="cols__5-span-3" tabindex="60"
                       value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="zipCodeId" class="cols__2-span-3">{$LANG.zip}:</label>
                <input type="text" name="zip_code" id="zipCodeId" class="cols__5-span-3" tabindex="70"
                       value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="countryId" class="cols__2-span-3">{$LANG.country}:</label>
                <input type="text" name="country" id="countryId" class="cols__5-span-3" tabindex="80"
                       value="{if isset($smarty.post.country)}{$smarty.post.country|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="phoneId" class="cols__2-span-3">{$LANG.phoneUc}:</label>
                <input type="text" name="phone" id="phoneId" class="cols__5-span-3" tabindex="90"
                       value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="mobilePhoneId" class="cols__2-span-3">{$LANG.mobilePhone}:</label>
                <input type="text" name="mobile_phone" id="mobilePhoneId" class="cols__5-span-3" tabindex="100"
                       value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="faxId" class="cols__2-span-3">{$LANG.fax}:</label>
                <input type="text" name="fax" id="faxId" class="cols__5-span-3" tabindex="110"
                       value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailId" class="cols__2-span-3">{$LANG.email}:</label>
                <input type="text" name="email" id="emailId" class="cols__5-span-6 validate[required,custom[email]]" tabindex="120"
                       value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardHolderNameId" class="cols__2-span-3">{$LANG.creditCardHolderName}:</label>
                <input type="text" name="credit_card_holder_name" id="creditCardHolderNameId" class="cols__5-span-6" tabindex="130"
                       value="{if isset($smarty.post.credit_card_holder_name)}{$smarty.post.credit_card_holder_name|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardNumberId" class="cols__2-span-3">{$LANG.creditCardNumber}:</label>
                <input type="text" name="credit_card_number" id="creditCardNumberId" class="cols__5-span-3" tabindex="140"
                       value="{if isset($smarty.post.credit_card_number)}{$smarty.post.credit_card_number|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardExpiryMonthId" class="cols__2-span-3">{$LANG.creditCardExpiryMonth}:</label>
                <select name="credit_card_expiry_month" id="creditCardExpiryMonthId" class="cols__5-span-1" tabindex="150">
                    <option></option>
                    {for $mon=1 to 12}
                        <option value="{$mon}"
                                {if isset($smarty.post.credit_card_expiry_month) &&
                                $smarty.post.credit_card_expiry_month==$mon}selected{/if}>{$mon|string_format:"%02d"}</option>
                    {/for}
                </select>
                <label for="creditCardExpiryYearId" class="cols__7-span-3">{$LANG.creditCardExpiryYear}:</label>
                <select name="credit_card_expiry_year" id="creditCardExpiryYearId" class="cols__10-span-1" tabindex="160">
                    <option></option>
                    {for $yr=21 to 30}
                        <option value="{$yr}"
                                {if isset($smarty.post.credit_card_expiry_year) &&
                                $smarty.post.credit_card_expiry_year==$yr}selected{/if}>{$yr|string_format:"20%02d"}</option>
                    {/for}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="parentCustomerId" class="cols__2-span-3">{$LANG.parentCustomer}:
                    <a class="cluetip" href="#" title="{$LANG.parentCustomer}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpParentCustomer">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                {if empty($parent_customers)}
                    <em>{$LANG.noCustomers}</em>
                {else}
                    <select name="parent_customer_id" id="parentCustomerId" class="cols__5-span-6" tabindex="170">
                        <option value="0"></option>
                        {foreach $parent_customers as $customer}
                            <option {if isset($defaultCustomerID) && $customer.id == $defaultCustomerID}selected{/if}
                                    value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">
                                {$customer.name|htmlSafe}
                            </option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            {if !empty($customFieldLabel.customer_cf1)}
                <div class="grid__container grid__head-10">
                    <label for="customField1Id" class="cols__2-span-3">{$customFieldLabel.customer_cf1|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <input type="text" name="custom_field1" id="customField1Id" class="cols__5-span-6" tabindex="175"
                           value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlSafe}{/if}"/>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf2)}
                <div class="grid__container grid__head-10">
                    <label for="customField2Id" class="cols__2-span-3">{$customFieldLabel.customer_cf2|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <input type="text" name="custom_field2" id="customField2Id" class="cols__5-span-6" tabindex="180"
                           value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf3)}
                <div class="grid__container grid__head-10">
                    <label for="customField3Id" class="cols__2-span-3">{$customFieldLabel.customer_cf3|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <input type="text" name="custom_field3" id="customField3Id" class="cols__5-span-6" tabindex="190"
                           value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf4)}
                <div class="grid__container grid__head-10">
                    <label for="customField4Id" class="cols__2-span-3">{$customFieldLabel.customer_cf4|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <input type="text" name="custom_field4" id="customField4Id" class="cols__5-span-6" tabindex="200"
                           value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                </div>
            {/if}
            <div class="grid__container grid__head-10">
                <label for="notes" class="cols__2-span-3">{$LANG.notes}:</label>
                <div class="cols__2-span-9 margin__top-0-5">
                    <input name="notes" id="notes" type="hidden"
                           {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if}>
                    <trix-editor input="notes" tabindex="210"></trix-editor>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__2-span-3">{$LANG.enabled}:</label>
                {html_options name=enabled id=enabledId class=cols__5-span-3 options=$enabled selected=$customer.enabled tabindex=220}
            </div>
        </div>
        <br/>
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="230">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=customers&amp;view=manage" class="button negative" tabindex="240">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
