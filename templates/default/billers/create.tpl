{*
 *  Script: create.tpl
 *      Biller add template
 *
 *  Last edited:
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
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=billers&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="name" class="cols__1-span-2">{$LANG.billerName}:
                    <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="name" id="name" tabindex="10" size="25"
                           class="margin__left-0-5 validate[required] text-input"
                           value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddressId" class="cols__1-span-2">{$LANG.street}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="street_address" id="streetAddressId" tabindex="20" size="25"
                           value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="streetAddress2Id" class="cols__1-span-2">{$LANG.street2}:
                    <a class="cluetip" href="#" title="{$LANG.street2}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="street_address2" id="streetAddress2Id" tabindex="30" size="25"
                           value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="cityId" class="cols__1-span-2">{$LANG.city}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="city" id="cityId" tabindex="40" size="25"
                           value="{if isset($smarty.post.city)}{$smarty.post.city|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="stateId" class="cols__1-span-2">{$LANG.state}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="state" id="stateId" tabindex="50" size="25"
                           value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="zipCodeId" class="cols__1-span-2">{$LANG.zip}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="zip_code" id="zipCodeId" tabindex="60" size="25"
                           value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="countryId" class="cols__1-span-2">{$LANG.country}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="country" id="countryId" tabindex="65" size="50"
                           value="{if isset($smarty.post.country)}{$smarty.post.country|htmlSafe}{/if}"/></div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="phoneId" class="cols__1-span-2">{$LANG.phoneUc}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="phone" id="phoneId" tabindex="70" size="25"
                           value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlSafe}{/if}"/></div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="mobilePhoneId" class="cols__1-span-2">{$LANG.mobilePhone}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="mobile_phone" id="mobilePhoneId" tabindex="75" size="25"
                           value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="faxId" class="cols__1-span-2">{$LANG.fax}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="fax" id="faxId" tabindex="80" size="25"
                           value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailId" class="cols__1-span-2">{$LANG.email}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="email" id="emailId" tabindex="90" size="25"
                           value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="signature" class="cols__1-span-2">{$LANG.signature}:</label>
                <div class="cols__3-span-8">
                    <input name="signature" id="signature" {if isset($smarty.post.signature)}value="{$smarty.post.signature|outHtml}"{/if} type="hidden">
                    <trix-editor class="margin__left-0-5" input="signature" tabindex="100"></trix-editor>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="paypalBusinessNameId" class="cols__1-span-2">{$LANG.paypalBusinessName}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="paypal_business_name" id="paypalBusinessNameId" tabindex="110" size="25"
                           value="{if isset($smarty.post.paypal_business_name)}{$smarty.post.paypal_business_name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="paypalNotifyUrlId" class="cols__1-span-2">{$LANG.paypalNotifyUrl}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="paypal_notify_url" id="paypalNotifyUrlId" tabindex="120" size="50"
                           value="{if isset($smarty.post.paypal_notify_url)}{$smarty.post.paypal_notify_url|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="paypalReturnUrlId" class="cols__1-span-2">{$LANG.paypalReturnUrl}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="paypal_return_url" id="paypalReturnUrlId" tabindex="130" size="50"
                           value="{if isset($smarty.post.paypal_return_url)}{$smarty.post.paypal_return_url|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="ewayCustomerId" class="cols__1-span-2">{$LANG.ewayCustomerId}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="eway_customer_id" id="ewayCustomerId" tabindex="140" size="50"
                           value="{if isset($smarty.post.eway_customer_id)}{$smarty.post.eway_customer_id|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="paymentsgatewayApiId" class="cols__1-span-2">{$LANG.paymentsGatewayApiId}:</label>
                <div class="cols__3-span-8">
                    <input type="text" name="paymentsgateway_api_id" id="paymentsgatewayApiId" tabindex="150" size="50"
                           value="{if isset($smarty.post.paymentsgateway_api_id)}{$smarty.post.paymentsgateway_api_id|htmlSafe}{/if}"/>
                </div>
            </div>
            {if !empty($customFieldLabel.biller_cf1)}
                <div class="grid__container grid__head-10">
                    <label for="customField1Id" class="cols__1-span-2">{$customFieldLabel.biller_cf1|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-8">
                        <input type="text" name="custom_field1" id="customField1Id" tabindex="160" size="25"
                               value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf2)}
                <div class="grid__container grid__head-10">
                    <label for="customField2Id" class="cols__1-span-2">{$customFieldLabel.biller_cf2}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-8">
                        <input type="text" name="custom_field2" id="customField2Id" tabindex="170" size="25"
                               value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf3)}
                <div class="grid__container grid__head-10">
                    <label for="customField3Id" class="cols__1-span-2">{$customFieldLabel.biller_cf3|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-8">
                        <input type="text" name="custom_field3" id="customField3Id" tabindex="180" size="25"
                               value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            {if !empty($customFieldLabel.biller_cf4)}
                <div class="grid__container grid__head-10">
                    <label for="customField4Id" class="cols__1-span-2">{$customFieldLabel.biller_cf4|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-8">
                        <input type="text" name="custom_field4" id="customField4Id" tabindex="190" size="25"
                               value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                    </div>
                </div>
            {/if}
            <div class="grid__container grid__head-10">
                <label for="logoId" class="cols__1-span-2">{$LANG.logoFile}:
                    <a class="cluetip" href="#" title="{$LANG.logoFile}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInsertBillerText">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    {html_options name=logo id=logoId class=margin__left-0-5 output=$files values=$files selected=$files[0] tabindex=200 }
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="footer" class="cols__1-span-2">{$LANG.invoiceFooter}:</label>
                <div class="cols__3-span-8">
                    <input name="footer" id="footer" {if isset($smarty.post.footer)}value="{$smarty.post.footer|outHtml}"{/if} type="hidden">
                    <trix-editor class="margin__left-0-5" input="footer" tabindex="210"></trix-editor>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="notes" class="cols__1-span-2">{$LANG.notes}:</label>
                <div class="cols__3-span-8">
                    <input name="notes" id="notes" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if} type="hidden">
                    <trix-editor class="margin__left-0-5" input="notes" tabindex="220"></trix-editor>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__1-span-2">{$LANG.enabled}:</label>
                <div class="cols__3-span-8">
                    {html_options name=enabled id=enabledId class=margin__left-0-5 options=$enabled selected=1 tabindex=230}
                </div>
            </div>
            <div class="align__text-center">
                <button type="submit" class="positive" name="submit" value="{$LANG.insertBiller}" tabindex="240">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=billers&amp;view=manage" class="button negative" tabindex="250">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
