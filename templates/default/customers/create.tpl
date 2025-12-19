{*
 *  Script: create.tpl
 *      New customer template
 *
 *  Last modified:
 *      20251215 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
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
    <div class="form__container">
        {if !empty($errorMsg)}
            <h3 class="align__text-center si_message_error">{$errorMsg}</h3>
        {/if}
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=customers&amp;view=create">
            <div class="row">
                <div class="col__25">
                    <label for="name">{$LANG.customerName}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpCustomerName}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="name" id="name" required tabindex="10" size="50"
                           autofocus placeholder="{$PLACEHOLDERS["name"]}"
                           value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="department">{$LANG.customerDepartment}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="department" id="department" tabindex="15" size="50"
                           value="{if isset($smarty.post.department)}{$smarty.post.department|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="attentionId">{$LANG.customerContact}:
                        <img class="tooltip" title="{$LANG.helpCustomerContact}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="attention" id="attentionId" tabindex="20" size="50"
                           value="{if isset($smarty.post.attention)}{$smarty.post.attention|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="streetAddressId">{$LANG.street}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="street_address" id="streetAddressId" tabindex="30" size="50"
                           value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="streetAddress2Id">{$LANG.street2}:
                        <img class="tooltip" title="{$LANG.helpStreet2}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="street_address2" id="streetAddress2Id" tabindex="40" size="50"
                           value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="cityId">{$LANG.city}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="city" id="cityId" tabindex="50" size="25" minlength="2"
                           value="{if isset($smarty.post.city)}{$smarty.post.city|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="stateId">{$LANG.state}:
                        <img class="tooltip" title="{$LANG.helpState}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="state" id="stateId" tabindex="60" size="3" minlength="2" maxlength="3"
                           class="transform__uppercase"
                           value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="zipCodeId">{$LANG.zip}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="zip_code" id="zipCodeId" tabindex="70" size="10" minlength="5"
                           placeholder="{$PLACEHOLDERS['zip_code']}"
                           value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="countryId">{$LANG.country}:
                        <img class="tooltip" title="{$LANG.helpCountry}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="country" id="countryId" tabindex="80" size="3" minlength="3" maxlength="3"
                           class="transform__uppercase"
                           value="{if isset($smarty.post.country)}{$smarty.post.country|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="phoneId">{$LANG.phoneUc}:
                        <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="phone" id="phoneId" tabindex="90" size="12"
                           placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="mobilePhoneId">{$LANG.mobilePhone}:
                        <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="mobile_phone" id="mobilePhoneId" tabindex="100" size="12"
                           placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="faxId">{$LANG.fax}:
                        <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="fax" id="faxId" tabindex="110" size="12"
                           placeholder="{$PLACEHOLDERS['tel']}"
                           value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="emailId">{$LANG.email}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailAddress}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="email" name="email" id="emailId" tabindex="120" size="50" required
                           placeholder="{$PLACEHOLDERS['email']}"
                           value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="creditCardNumberId">{$LANG.creditCardNumber}:
                        <img class="tooltip" title="{$LANG.helpCreditCardNumber}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="credit_card_number" id="creditCardNumberId" tabindex="140" size="25"
                           minlength="13" class="cc-group"
                           value="{if isset($smarty.post.credit_card_number)}{$smarty.post.credit_card_number|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="creditCardHolderNameId">{$LANG.creditCardHolderName}:
                        <img class="tooltip" title="{$LANG.helpCreditCardHolderName}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="credit_card_holder_name" id="creditCardHolderNameId" tabindex="130"
                           size="50"
                           minlength="2" class="cc-group"
                           value="{if isset($smarty.post.credit_card_holder_name)}{$smarty.post.credit_card_holder_name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="creditCardExpiryMonthId">{$LANG.creditCardExpiryMonth}:
                        <img class="tooltip" title="{$LANG.helpCreditCardExpiryMonth}"
                             src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <select name="credit_card_expiry_month" id="creditCardNumberId" tabindex="150" class="cc-group">
                        <option></option>
                        {for $mon=1 to 12}
                            <option value="{$mon}"
                                    {if isset($smarty.post.credit_card_expiry_month) &&
                                    $smarty.post.credit_card_expiry_month==$mon}selected{/if}>{$mon|string_format:"%02d"}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="creditCardExpiryYearId">{$LANG.creditCardExpiryYear}:
                        <img class="tooltip" title="{$LANG.helpCreditCardExpiryYear}"
                             src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <select name="credit_card_expiry_year" id="creditCardExpiryYearId" tabindex="160" class="cc-group">
                        <option></option>
                        {for $yr=21 to 40}
                            <option value="{$yr}"
                                    {if isset($smarty.post.credit_card_expiry_year) &&
                                    $smarty.post.credit_card_expiry_year==$yr}selected{/if}>{$yr+2000|string_format:"%04d"}</option>
                        {/for}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="parentCustomerId">{$LANG.parentCustomer}:
                        <img class="tooltip" title="{$LANG.helpParentCustomer}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    {if empty($parent_customers)}
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <select name="parent_customer_id" id="parentCustomerId" tabindex="170">
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
                <div class="row">
                    <div class="col__25">
                        <label for="customField1Id">{$customFieldLabel.customer_cf1|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field1" id="customField1Id" tabindex="175" size="50"
                               value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf2)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField2Id">{$customFieldLabel.customer_cf2|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field2" id="customField2Id" tabindex="180" size="50"
                               value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf3)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField3Id">{$customFieldLabel.customer_cf3|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field3" id="customField3Id" tabindex="190" size="50"
                               value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.customer_cf4)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField4Id">{$customFieldLabel.customer_cf4|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field4" id="customField4Id" tabindex="200" size="50"
                               value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            <div class="row">
                <div class="col__25 align__text-left">
                    <label for="notes">{$LANG.notes}:</label>
                </div>
            </div>
            <div class="row">
                <div class="col__100">
                    <input name="notes" id="notes" type="hidden"
                           {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if}>
                    <trix-toolbar id="trix-toolbar"></trix-toolbar>
                    <trix-editor input="notes" toolbar="trix-toolbar"
                                 tabindex="210"></trix-editor>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="enabledId">{$LANG.enabled}:</label>
                </div>
                <div class="col__75">
                    {html_options name=enabled id=enabledId options=$enabled selected=$customer.enabled tabindex=220}
                </div>
            </div>
            <div class="align__text-center margin__top-2">
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
    </div>
{/if}
