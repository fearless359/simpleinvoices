{*
 *  Script: edit.tpl
 *      Biller edit template
 *
 *  Last edited:
 *      20210615 by Rich Rowley to convert to grid layout.
 *      20180921 by Rich Rowley to add signature field.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=billers&amp;view=save&amp;id={$smarty.get.id}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="name" class="cols__1-span-2">{$LANG.billerName}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpBillerName}"
                         src="{$helpImagePath}required-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="name" id="name" tabindex="10" size="50" required minlength="4"
                       class="margin__left-0-5"
                       value="{if isset($biller.name)}{$biller.name|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddressId" class="cols__1-span-2">{$LANG.street}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="street_address" id="streetAddressId" tabindex="20" size="50" minlength="4"
                       class="margin__left-0-5"
                       value="{if isset($biller.street_address)}{$biller.street_address|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddress2Id" class="cols__1-span-2">{$LANG.street2}:
                <img class="tooltip" title="{$LANG.helpStreet2}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="street_address2" id="streetAddress2Id" tabindex="30" size="50" minlength="2"
                       class="margin__left-0-5"
                       value="{if isset($biller.street_address2)}{$biller.street_address2|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="cityId" class="cols__1-span-2">{$LANG.city}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="city" id="cityId" tabindex="40" size="25" minlength="2"
                       class="margin__left-0-5"
                       value="{if isset($biller.city)}{$biller.city|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="stateId" class="cols__1-span-2">{$LANG.state}:
                <img class="tooltip" title="{$LANG.helpState}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="state" id="stateId" tabindex="50" size="3" minlength="2" maxlength="3"
                       class="margin__left-0-5 transform__uppercase"
                       value="{if isset($biller.state)}{$biller.state|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="zipCodeId" class="cols__1-span-2">{$LANG.zip}:
                <img class="tooltip" title="{$LANG.helpZipCode}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="zip_code" id="zipCodeId" tabindex="60" size="10" minlength="5"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['zip_code']}"
                       value="{if isset($biller.zip_code)}{$biller.zip_code|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="countryId" class="cols__1-span-2">{$LANG.country}:
                <img class="tooltip" title="{$LANG.helpCountry}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="country" id="countryId" tabindex="70" size="3" minlength="3" maxlength="3"
                       class="margin__left-0-5 transform__uppercase" placeholder="{$PLACEHOLDERS['country']}"
                       value="{if isset($biller.country)}{$biller.country|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="phoneId" class="cols__1-span-2">{$LANG.phoneUc}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                     src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="phone" id="phoneId" tabindex="80" size="12"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['tel']}"
                       value="{if isset($biller.phone)}{$biller.phone|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="mobilePhoneId" class="cols__1-span-2">{$LANG.mobilePhone}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                     src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="mobile_phone" id="mobilePhoneId" tabindex="90" size="12"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['tel']}"
                       value="{if isset($biller.mobile_phone)}{$biller.mobile_phone|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="faxId" class="cols__1-span-2">{$LANG.fax}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}"
                     src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="fax" id="faxId" tabindex="100" size="12"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['tel']}"
                       value="{if isset($biller.fax)}{$biller.fax|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailId" class="cols__1-span-2">{$LANG.email}:
                <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailAddress}"
                     src="{$helpImagePath}required-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                <input type="text" name="email" id="emailId" tabindex="110" size="50" required
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['email']}"
                       value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="signature" class="cols__1-span-2">{$LANG.signature}:
                <img class="tooltip" title="{$LANG.helpSignature}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="signature" id="signature" type="hidden"
                       {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if}>
                <trix-editor class="margin__left-0-5" input="signature" tabindex="120"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
        <label for="paypalBusinessNameId" class="cols__1-span-2">{$LANG.paypalBusinessName}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="paypal_business_name" id="paypalBusinessNameId" tabindex="130" size="50"
                       class="margin__left-0-5"
                       value="{if isset($biller.paypal_business_name)}{$biller.paypal_business_name|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paypalNotifyUrlId" class="cols__1-span-2">{$LANG.paypalNotifyUrl}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="paypal_notify_url" id="paypalNotifyUrlId" tabindex="140" size="50"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['url']}"
                       value="{if isset($biller.paypal_notify_url)}{$biller.paypal_notify_url|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paypalReturnUrlId" class="cols__1-span-2">{$LANG.paypalReturnUrl}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="paypal_return_url" id="paypalReturnUrlId" tabindex="150" size="50"
                       class="margin__left-0-5" placeholder="{$PLACEHOLDERS['url']}"
                       value="{if isset($biller.paypal_return_url)}{$biller.paypal_return_url|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="ewayCustomerId" class="cols__1-span-2">{$LANG.ewayCustomerId}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="eway_customer_id" id="ewayCustomerId" tabindex="160" size="25"
                       class="margin__left-0-5"
                       value="{if isset($biller.eway_customer_id)}{$biller.eway_customer_id|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paymentsGatewayApiId" class="cols__1-span-2">{$LANG.paymentsGatewayApiId}:</label>
            <div class="cols__3-span-8">
                <input type="text" name="paymentsgateway_api_id" id="paymentsGatewayApiId" tabindex="170" size="50"
                       class="margin__left-0-5"
                       value="{if isset($biller.paymentsgateway_api_id)}{$biller.paymentsgateway_api_id|htmlSafe}{/if}"/>
            </div>
        </div>
        {if !empty($customFieldLabel.biller_cf1)}
            <div class="grid__container grid__head-10">
                <label for="customField1Id" class="cols__1-span-2">{$customFieldLabel.biller_cf1|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="custom_field1" id="customField1Id" tabindex="180" size="50"
                           class="margin__left-0-5"
                           value="{if isset($biller.custom_field1)}{$biller.custom_field1|htmlSafe}{/if}">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf2)}
            <div class="grid__container grid__head-10">
                <label for="customField2Id" class="cols__1-span-2">{$customFieldLabel.biller_cf2|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="custom_field2" id="customField2Id" tabindex="190" size="50"
                           class="margin__left-0-5"
                           value="{if isset($biller.custom_field2)}{$biller.custom_field2}{/if}">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf3)}
            <div class="grid__container grid__head-10">
                <label for="customField3Id" class="cols__1-span-2">{$customFieldLabel.biller_cf3|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="custom_field3" id="customField3Id" tabindex="200" size="50"
                           class="margin__left-0-5"
                           value="{if isset($biller.custom_field3)}{$biller.custom_field3|htmlSafe}{/if}">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf4)}
            <div class="grid__container grid__head-10">
                <label for="customField4Id" class="cols__1-span-2">{$customFieldLabel.biller_cf4|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" name="custom_field4" id="customField4Id" tabindex="210" size="50"
                           class="margin__left-0-5"
                           value="{if isset($biller.custom_field4)}{$biller.custom_field4|htmlSafe}{/if}">
                </div>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="logoId" class="cols__1-span-2">{$LANG.logoFile}:
                <img class="tooltip" title="{$LANG.helpInsertBillerText}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__3-span-8">
                {html_options name=logo id=logoId class=margin__left-0-5
                              output=$files values=$files selected=$biller.logo tabindex=220}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="footer" class="cols__1-span-2">{$LANG.invoiceFooter}:
                <img class="tooltip" title="{$LANG.helpBillerInvoiceFooter}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="footer" id="footer" {if isset($biller.footer)}value="{$biller.footer|outHtml}"{/if} type="hidden">
                <trix-editor class="margin__left-0-5" input="footer" tabindex="230"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="notes" class="cols__1-span-2">{$LANG.notes}:</label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="notes" id="notes" {if isset($biller.notes)}value="{$biller.notes|outHtml}"{/if} type="hidden">
                <trix-editor class="margin__left-0-5" input="notes" tabindex="240"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__1-span-2">{$LANG.enabled}:</label>
            <div class="cols__3-span-8">
                {html_options name=enabled id=enabledId class=margin__left-0-5
                              options=$enabled selected=$biller.enabled tabindex=250}
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="save_biller" value="{$LANG.saveBiller}" tabindex="260">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=billers&amp;view=manage" class="button negative" tabindex="270">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="edit">
    <input type="hidden" name="category" value="1"/>
    <input type="hidden" name="domain_id" value="{if isset($biller.domain_id)}{$biller.domain_id}{/if}"/>
</form>
