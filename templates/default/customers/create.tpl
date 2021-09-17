{*
 *  Script: create.tpl
 *      New customer template
 *
 *  Last modified:
 *      20210827 by Richard Rowley to update for new attributes and field validation.
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if !empty($smarty.post.name) && isset($smarty.post.submit)}
    {* if customer is updated or saved.*}
    {include file="templates/default/customers/save.tpl"}
{else}
    {if !empty($errorMsg)}
        <h3 class="align__text-center si_message_error">{$errorMsg}</h3>
    {/if}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=customers&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="name" class="cols__1-span-3 align__text-right">{$LANG.customerName}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpCustomerName}"
                         src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="name" id="name" required tabindex="10" size="50"
                           class="margin__left-1" autofocus
                           value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="department" class="cols__1-span-3 align__text-right">{$LANG.customerDepartment}:</label>
                <div class="cols__4-span-7">
                    <input type="text" name="department" id="department" tabindex="15" size="50"
                           class="margin__left-1"
                           value="{if isset($smarty.post.department)}{$smarty.post.department|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="attentionId" class="cols__1-span-3 align__text-right">{$LANG.customerContact}:
                    <img class="tooltip" title="{$LANG.helpCustomerContact}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="attention" id="attentionId" tabindex="20" size="50"
                           class="margin__left-1"
                           value="{if isset($smarty.post.attention)}{$smarty.post.attention|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddressId" class="cols__1-span-3 align__text-right">{$LANG.street}:</label>
                <div class="cols__4-span-7">
                    <input type="text" name="street_address" id="streetAddressId" tabindex="30" size="50"
                           class="margin__left-1"
                           value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddress2Id" class="cols__1-span-3 align__text-right">{$LANG.street2}:
                    <img class="tooltip" title="{$LANG.helpStreet2}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="street_address2" id="streetAddress2Id" tabindex="40" size="50"
                           class="margin__left-1"
                           value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="cityId" class="cols__1-span-3 align__text-right">{$LANG.city}:</label>
                <div class="cols__4-span-7">
                    <input type="text" name="city" id="cityId" tabindex="50" size="25" minlength="2"
                           class="margin__left-1"
                           value="{if isset($smarty.post.city)}{$smarty.post.city|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="stateId" class="cols__1-span-3 align__text-right">{$LANG.state}:
                    <img class="tooltip" title="{$LANG.helpState}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="state" id="stateId" tabindex="60" size="3" minlength="2" maxlength="3"
                           class="margin__left-1 transform__uppercase"
                           value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="zipCodeId" class="cols__1-span-3 align__text-right">{$LANG.zip}:</label>
                <div class="cols__4-span-7">
                    <input type="text" name="zip_code" id="zipCodeId" tabindex="70" size="10" minlength="5"
                           class="margin__left-1" placeholder="{$PLACEHOLDERS['zip_code']}"
                           value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="countryId" class="cols__1-span-3 align__text-right">{$LANG.country}:
                    <img class="tooltip" title="{$LANG.helpCountry}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="country" id="countryId" tabindex="80" size="3" minlength="3" maxlength="3"
                           class="margin__left-1 transform__uppercase"
                           value="{if isset($smarty.post.country)}{$smarty.post.country|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="phoneId" class="cols__1-span-3 align__text-right">{$LANG.phoneUc}:
                    <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="phone" id="phoneId" tabindex="90" size="12"
                           class="margin__left-1" placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="mobilePhoneId" class="cols__1-span-3 align__text-right">{$LANG.mobilePhone}:
                    <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="mobile_phone" id="mobilePhoneId" tabindex="100" size="12"
                           class="margin__left-1" placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="faxId" class="cols__1-span-3 align__text-right">{$LANG.fax}:
                    <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="fax" id="faxId" tabindex="110" size="12"
                           class="margin__left-1" placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailId" class="cols__1-span-3 align__text-right">{$LANG.email}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailAddress}"
                         src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="email" name="email" id="emailId" tabindex="120" size="50" required
                           class="margin__left-1" placeholder="{$PLACEHOLDERS['email']}"
                           value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardNumberId" class="cols__1-span-3 align__text-right">{$LANG.creditCardNumber}:
                    <img class="tooltip" title="{$LANG.helpCreditCardNumber}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="credit_card_number" id="creditCardNumberId" tabindex="140" size="25" minlength="13"
                           class="cc-group margin__left-1"
                           value="{if isset($smarty.post.credit_card_number)}{$smarty.post.credit_card_number|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardHolderNameId" class="cols__1-span-3 align__text-right">{$LANG.creditCardHolderName}:
                    <img class="tooltip" title="{$LANG.helpCreditCardHolderName}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <input type="text" name="credit_card_holder_name" id="creditCardHolderNameId" tabindex="130" size="50" minlength="2"
                           class="cc-group margin__left-1"
                           value="{if isset($smarty.post.credit_card_holder_name)}{$smarty.post.credit_card_holder_name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardExpiryMonthId" class="cols__1-span-3 align__text-right">{$LANG.creditCardExpiryMonth}:
                    <img class="tooltip" title="{$LANG.helpCreditCardExpiryMonth}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <select name="credit_card_expiry_month" id="creditCardNumberId" tabindex="150"
                            class="cc-group margin__left-1">
                        <option></option>
                        {for $mon=1 to 12}
                            <option value="{$mon}"
                                    {if isset($smarty.post.credit_card_expiry_month) &&
                                    $smarty.post.credit_card_expiry_month==$mon}selected{/if}>{$mon|string_format:"%02d"}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="creditCardExpiryYearId" class="cols__1-span-3 align__text-right">{$LANG.creditCardExpiryYear}:
                    <img class="tooltip" title="{$LANG.helpCreditCardExpiryYear}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    <select name="credit_card_expiry_year" id="creditCardExpiryYearId" tabindex="160"
                            class="cc-group margin__left-1">
                        <option></option>
                        {for $yr=21 to 40}
                            <option value="{$yr}"
                                    {if isset($smarty.post.credit_card_expiry_year) &&
                                    $smarty.post.credit_card_expiry_year==$yr}selected{/if}>{$yr+2000|string_format:"%04d"}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="parentCustomerId" class="cols__1-span-3 align__text-right">{$LANG.parentCustomer}:
                    <img class="tooltip" title="{$LANG.helpParentCustomer}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__4-span-7">
                    {if empty($parent_customers)}
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <select name="parent_customer_id" id="parentCustomerId" tabindex="170"
                                class="margin__left-1">
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
            </div>
            {if !empty($customFieldLabel.customer_cf1)}
                <div class="grid__container grid__head-10">
                    <label for="customField1Id" class="cols__1-span-3 align__text-right">{$customFieldLabel.customer_cf1|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <div class="cols__4-span-7">
                        <input type="text" name="custom_field1" id="customField1Id" tabindex="175" size="50"
                               class="margin__left-1"
                               value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf2)}
                <div class="grid__container grid__head-10">
                    <label for="customField2Id" class="cols__1-span-3 align__text-right">{$customFieldLabel.customer_cf2|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <div class="cols__4-span-7">
                        <input type="text" name="custom_field2" id="customField2Id" tabindex="180" size="50"
                               class="margin__left-1"
                               value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf3)}
                <div class="grid__container grid__head-10">
                    <label for="customField3Id" class="cols__1-span-3 align__text-right">{$customFieldLabel.customer_cf3|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <div class="cols__4-span-7">
                        <input type="text" name="custom_field3" id="customField3Id" tabindex="190" size="50"
                               class="margin__left-1"
                               value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf4)}
                <div class="grid__container grid__head-10">
                    <label for="customField4Id" class="cols__1-span-3 align__text-right">{$customFieldLabel.customer_cf4|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <div class="cols__4-span-7">
                        <input type="text" name="custom_field4" id="customField4Id" tabindex="200" size="50"
                               class="margin__left-1"
                               value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            <div class="grid__container grid__head-10">
                <label for="notes" class="cols__2-span-4">{$LANG.notes}:</label>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-8">
                    <input name="notes" id="notes" type="hidden"
                           {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if}>
                    <trix-toolbar id="trix-toolbar" class="margin__left-1"></trix-toolbar>
                    <trix-editor input="notes" class="margin__left-1" toolbar="trix-toolbar" tabindex="210"></trix-editor>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__1-span-3 align__text-right">{$LANG.enabled}:</label>
                <div class="cols__4-span-7">
                     {html_options name=enabled id=enabledId class='margin__left-1'
                                   options=$enabled selected=$customer.enabled tabindex=220}
                </div>
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
