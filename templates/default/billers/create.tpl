{*
 *  Script: create.tpl
 *      Biller add template
 *
 *  Last edited:
 *      20251212 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210827 by Richard Rowley to update for new attributes and field validation.
 *      20210615 by Rich Rowley to convert to grid layout.
 *      20160116 by Rich Rowley to add signature field.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
{if !empty($smarty.post.name) && isset($smarty.post.submit) }
    {include file="templates/default/billers/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=billers&amp;view=create">
            <div class="row">
                <div class="col__25">
                    <label for="name">{$LANG.billerName}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpBillerName}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="name" id="name" tabindex="10" size="50" required minlength="4"
                           autofocus placeholder="{$PLACEHOLDERS["name"]}"
                           value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="streetAddressId">{$LANG.street}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="street_address" id="streetAddressId" tabindex="20" size="50" minlength="4"
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
                    <input type="text" name="street_address2" id="streetAddress2Id" tabindex="30" size="50"
                           minlength="2"
                           value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="cityId">{$LANG.city}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="city" id="cityId" tabindex="40" size="25" minlength="2"
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
                    <input type="text" name="state" id="stateId" tabindex="50" size="3" minlength="2" maxlength="3"
                           class="transform__uppercase"
                           value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="zipCodeId">{$LANG.zip}:
                        <img class="tooltip" title="{$LANG.helpZipCode}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="zip_code" id="zipCodeId" tabindex="60" size="10" minlength="5"
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
                    <input type="text" name="country" id="countryId" tabindex="70" size="3" minlength="3" maxlength="3"
                           class="transform__uppercase" placeholder="{$PLACEHOLDERS['country']}"
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
                    <input type="tel" name="phone" id="phoneId" tabindex="80" size="12"
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
                    <input type="tel" name="mobile_phone" id="mobilePhoneId" tabindex="90" size="12"
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
                    <input type="tel" name="fax" id="faxId" tabindex="100" size="12"
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
                    <input type="email" name="email" id="emailId" tabindex="110" size="50" required
                           placeholder="{$PLACEHOLDERS['email']}"
                           value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25 align__text-left">
                    <label for="signature">{$LANG.signature}:
                        <img class="tooltip" title="{$LANG.helpSignature}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class='col__100'>
                    <input name="signature" id="signature" type="hidden"
                           {if isset($smarty.post.signature)}value="{$smarty.post.signature|outHtml}"{/if}>
                    <trix-editor class="trix-content" input="signature" tabindex="120"></trix-editor>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="paypalBusinessNameId">{$LANG.paypalBusinessName}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="paypal_business_name" id="paypalBusinessNameId" tabindex="130" size="50"
                           value="{if isset($smarty.post.paypal_business_name)}{$smarty.post.paypal_business_name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="paypalNotifyUrlId">{$LANG.paypalNotifyUrl}:</label>
                </div>
                <div class="col__75">
                    <input type="url" name="paypal_notify_url" id="paypalNotifyUrlId" tabindex="140" size="50"
                           placeholder="{$PLACEHOLDERS['url']}"
                           value="{if isset($smarty.post.paypal_notify_url)}{$smarty.post.paypal_notify_url|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="paypalReturnUrlId">{$LANG.paypalReturnUrl}:</label>
                </div>
                <div class="col__75">
                    <input type="url" name="paypal_return_url" id="paypalReturnUrlId" tabindex="150" size="50"
                           placeholder="{$PLACEHOLDERS['url']}"
                           value="{if isset($smarty.post.paypal_return_url)}{$smarty.post.paypal_return_url|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="ewayCustomerId">{$LANG.ewayCustomerId}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="eway_customer_id" id="ewayCustomerId" tabindex="160" size="25"
                           placeholder="12345678"
                           value="{if isset($smarty.post.eway_customer_id)}{$smarty.post.eway_customer_id|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="paymentsgatewayApiId">{$LANG.paymentsGatewayApiId}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="paymentsgateway_api_id" id="paymentsgatewayApiId" tabindex="170" size="50"
                           value="{if isset($smarty.post.paymentsgateway_api_id)}{$smarty.post.paymentsgateway_api_id|htmlSafe}{/if}"/>
                </div>
            </div>
            {if !empty($customFieldLabel.biller_cf1)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField1Id">{$customFieldLabel.biller_cf1|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field1" id="customField1Id" tabindex="180" size="50"
                               value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf2)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField2Id">{$customFieldLabel.biller_cf2}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field2" id="customField2Id" tabindex="190" size="50"
                               value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf3)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField3Id">{$customFieldLabel.biller_cf3|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field3" id="customField3Id" tabindex="200" size="50"
                               value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf4)}
                <div class="row">
                    <div class="col__25">
                        <label for="customField4Id">{$customFieldLabel.biller_cf4|htmlSafe}:
                            <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__75">
                        <input type="text" name="custom_field4" id="customField4Id" tabindex="210" size="50"
                               value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            <div class="row">
                <div class="col__25">
                    <label for="logoId">{$LANG.logoFile}:
                        <img class="tooltip" title="{$LANG.helpInsertBillerText}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    {html_options name=logo id=logoId class="margin__left-0-5"
                    output=$files values=$files selected=$files[0] tabindex=220 }
                </div>
            </div>
            <div class="row">
                <div class="col__25 align__text-left">
                    <label for="footer">{$LANG.invoiceFooter}:
                        <img class="tooltip" title="{$LANG.helpBillerInvoiceFooter}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class='col__100'>
                    <input name="footer" id="footer"
                           {if isset($smarty.post.footer)}value="{$smarty.post.footer|outHtml}"{/if} type="hidden">
                    <trix-editor class="trix-content" input="footer" tabindex="230"></trix-editor>
                </div>
            </div>
            <div class="row">
                <div class="col__25 align__text-left">
                    <label for="notes">{$LANG.notes}:</label>
                </div>
            </div>
            <div class="row">
                <div class="col__100">
                    <input name="notes" id="notes"
                           {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if} type="hidden">
                    <trix-editor class="trix-content" input="notes" tabindex="240"></trix-editor>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="enabledId">{$LANG.enabled}:</label>
                </div>
                <div class="col__75">
                    {html_options name=enabled id=enabledId class="margin__left-0-5"
                    options=$enabled selected=1 tabindex=250}
                </div>
            </div>
            <div class="align__text-center margin__top-2">
                <button type="submit" class="positive" name="submit" value="{$LANG.insertBiller}" tabindex="260">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=billers&amp;view=manage" class="button negative" tabindex="270">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="op" value="create"/>
            <input type="hidden" name="domain_id" value="{$domain_id}"/>
        </form>
    </div>
{/if}
