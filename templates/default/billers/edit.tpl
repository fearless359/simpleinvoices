{*
 *  Script: edit.tpl
 *      Biller edit template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
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
    <div class="flex__area">
        <div class="flex__container flex__start">
            <label for="name" class="margin__right-1">{$LANG.billerName}:
                <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpBillerName}"
                     src="{$helpImagePath}required-small.png" alt=""/>
            </label>
            <input type="text" name="name" id="name" tabindex="10" size="50" required minlength="4"
                   value="{if isset($biller.name)}{$biller.name|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="streetAddressId" class="margin__right-1">{$LANG.street}:</label>
            <input type="text" name="street_address" id="streetAddressId" tabindex="20" size="50" minlength="4"
                   value="{if isset($biller.street_address)}{$biller.street_address|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="streetAddress2Id" class="margin__right-1">{$LANG.street2}:
                <img class="tooltip" title="{$LANG.helpStreet2}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="street_address2" id="streetAddress2Id" tabindex="30" size="50" minlength="2"
                   value="{if isset($biller.street_address2)}{$biller.street_address2|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="cityId" class="margin__right-1">{$LANG.city}:</label>
            <input type="text" name="city" id="cityId" tabindex="40" size="25" minlength="2"
                   value="{if isset($biller.city)}{$biller.city|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="stateId" class="margin__right-1">{$LANG.state}:
                <img class="tooltip" title="{$LANG.helpState}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="state" id="stateId" tabindex="50" size="3" minlength="2" maxlength="3"
                   value="{if isset($biller.state)}{$biller.state|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="zipCodeId" class="margin__right-1">{$LANG.zip}:
                <img class="tooltip" title="{$LANG.helpZipCode}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="zip_code" id="zipCodeId" tabindex="60" size="10" minlength="5"
                   placeholder="{$PLACEHOLDERS['zip_code']}"
                   value="{if isset($biller.zip_code)}{$biller.zip_code|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="countryId" class="margin__right-1">{$LANG.country}:
                <img class="tooltip" title="{$LANG.helpCountry}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="country" id="countryId" tabindex="70" size="3" minlength="3" maxlength="3"
                   class="transform__uppercase" placeholder="{$PLACEHOLDERS['country']}"
                   value="{if isset($biller.country)}{$biller.country|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="phoneId" class="margin__right-1">{$LANG.phoneUc}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="phone" id="phoneId" tabindex="80" size="12" placeholder="{$PLACEHOLDERS['tel']}"
                   value="{if isset($biller.phone)}{$biller.phone|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="mobilePhoneId" class="margin__right-1">{$LANG.mobilePhone}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="mobile_phone" id="mobilePhoneId" tabindex="90" size="12"
                   placeholder="{$PLACEHOLDERS['tel']}"
                   value="{if isset($biller.mobile_phone)}{$biller.mobile_phone|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="faxId" class="margin__right-1">{$LANG.fax}:
                <img class="tooltip" title="{$LANG.helpPhoneNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="fax" id="faxId" tabindex="100" size="12" placeholder="{$PLACEHOLDERS['tel']}"
                   value="{if isset($biller.fax)}{$biller.fax|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="emailId" class="margin__right-1">{$LANG.email}:
                <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailAddress}"
                     src="{$helpImagePath}required-small.png" alt=""/>
            </label>
            <input type="text" name="email" id="emailId" tabindex="110" size="50" required
                   placeholder="{$PLACEHOLDERS['email']}"
                   value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="signature">{$LANG.signature}:
                <img class="tooltip" title="{$LANG.helpSignature}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="signature" id="signature" type="hidden"
                       {if isset($biller.signature)}value="{$biller.signature|htmlSafe}"{/if}>
                <trix-editor input="signature" tabindex="120"></trix-editor>
            </div>
        </div>
        <div class="flex__container flex__start">
            <label for="paypalBusinessNameId" class="margin__right-1">{$LANG.paypalBusinessName}:</label>
            <input type="text" name="paypal_business_name" id="paypalBusinessNameId" tabindex="130" size="50"
                   value="{if isset($biller.paypal_business_name)}{$biller.paypal_business_name|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="paypalNotifyUrlId" class="margin__right-1">{$LANG.paypalNotifyUrl}:</label>
            <input type="text" name="paypal_notify_url" id="paypalNotifyUrlId" tabindex="140" size="50"
                   placeholder="{$PLACEHOLDERS['url']}"
                   value="{if isset($biller.paypal_notify_url)}{$biller.paypal_notify_url|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="paypalReturnUrlId" class="margin__right-1">{$LANG.paypalReturnUrl}:</label>
            <input type="text" name="paypal_return_url" id="paypalReturnUrlId" tabindex="150" size="50"
                   placeholder="{$PLACEHOLDERS['url']}"
                   value="{if isset($biller.paypal_return_url)}{$biller.paypal_return_url|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="ewayCustomerId" class="margin__right-1">{$LANG.ewayCustomerId}:</label>
            <input type="text" name="eway_customer_id" id="ewayCustomerId" tabindex="160" size="25"
                   placeholder="12345678"
                   value="{if isset($biller.eway_customer_id)}{$biller.eway_customer_id|htmlSafe}{/if}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="paymentsGatewayApiId" class="margin__right-1">{$LANG.paymentsGatewayApiId}:</label>
            <input type="text" name="paymentsgateway_api_id" id="paymentsGatewayApiId" tabindex="170" size="50"
                   value="{if isset($biller.paymentsgateway_api_id)}{$biller.paymentsgateway_api_id|htmlSafe}{/if}"/>
        </div>
        {if !empty($customFieldLabel.biller_cf1)}
            <div class="flex__container flex__start">
                <label for="customField1Id" class="margin__right-1">{$customFieldLabel.biller_cf1|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="custom_field1" id="customField1Id" tabindex="180" size="50"
                       value="{if isset($biller.custom_field1)}{$biller.custom_field1|htmlSafe}{/if}">
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf2)}
            <div class="flex__container flex__start">
                <label for="customField2Id" class="margin__right-1">{$customFieldLabel.biller_cf2|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="custom_field2" id="customField2Id" tabindex="190" size="50"
                       value="{if isset($biller.custom_field2)}{$biller.custom_field2}{/if}">
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf3)}
            <div class="flex__container flex__start">
                <label for="customField3Id" class="margin__right-1">{$customFieldLabel.biller_cf3|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="custom_field3" id="customField3Id" tabindex="200" size="50"
                       value="{if isset($biller.custom_field3)}{$biller.custom_field3|htmlSafe}{/if}">
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf4)}
            <div class="flex__container flex__start">
                <label for="customField4Id" class="margin__right-1">{$customFieldLabel.biller_cf4|htmlSafe}:
                    <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="custom_field4" id="customField4Id" tabindex="210" size="50"
                       value="{if isset($biller.custom_field4)}{$biller.custom_field4|htmlSafe}{/if}">
            </div>
        {/if}
        <div class="flex__container flex__start">
            <label for="logoId" class="margin__right-1">{$LANG.logoFile}:
                <img class="tooltip" title="{$LANG.helpInsertBillerText}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            {html_options name=logo id=logoId output=$files values=$files selected=$biller.logo tabindex=220}
        </div>
        <div class="flex__container flex__start">
            <label for="footer">{$LANG.invoiceFooter}:
                <img class="tooltip" title="{$LANG.helpBillerInvoiceFooter}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="footer" id="footer" {if isset($biller.footer)}value="{$biller.footer|htmlSafe}"{/if} type="hidden">
                <trix-editor input="footer" tabindex="230"></trix-editor>
            </div>
        </div>
        <div class="flex__container flex__start">
            <label for="notes">{$LANG.notes}:</label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">
                <input name="notes" id="notes" {if isset($biller.notes)}value="{$biller.notes|htmlSafe}"{/if} type="hidden">
                <trix-editor input="notes" tabindex="240"></trix-editor>
            </div>
        </div>
        <div class="flex__container flex__start">
            <label for="enabledId" class="margin__right-1">{$LANG.enabled}:</label>
             {html_options name=enabled id=enabledId options=$enabled selected=$biller.enabled tabindex=250}
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
