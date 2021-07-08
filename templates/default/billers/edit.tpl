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
                <a class="cluetip" href="#" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField"
                   title="{$LANG.requiredField}">
                    <img src="{$helpImagePath}required-small.png" alt=""/>
                </a>
            </label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5 validate[required]" name="name" id="name" tabindex="10"
                       value="{if isset($biller.name)}{$biller.name|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddressId" class="cols__1-span-2">{$LANG.street}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="street_address" id="streetAddressId" tabindex="20"
                       value="{if isset($biller.street_address)}{$biller.street_address|htmlSafe}{/if}" size="50"/></div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="streetAddress2Id" class="cols__1-span-2">{$LANG.street2}:
                <a class="cluetip" href="#" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2"
                   title="{$LANG.street2}">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="street_address2" id="streetAddress2Id" tabindex="30"
                       value="{if isset($biller.street_address2)}{$biller.street_address2|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="cityId" class="cols__1-span-2">{$LANG.city}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="city" id="cityId" tabindex="40"
                       value="{if isset($biller.city)}{$biller.city|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="zipCodeId" class="cols__1-span-2">{$LANG.zip}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="zip_code" id="zipCodeId" tabindex="50"
                       value="{if isset($biller.zip_code)}{$biller.zip_code|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="stateId" class="cols__1-span-2">{$LANG.state}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="state" id="stateId" tabindex="60"
                       value="{if isset($biller.state)}{$biller.state|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="countryId" class="cols__1-span-2">{$LANG.country}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="country" id="countryId" tabindex="70"
                       value="{if isset($biller.country)}{$biller.country|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="mobilePhoneId" class="cols__1-span-2">{$LANG.mobilePhone}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="mobile_phone" id="mobilePhoneId" tabindex="80"
                       value="{if isset($biller.mobile_phone)}{$biller.mobile_phone|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="phoneId" class="cols__1-span-2">{$LANG.phoneUc}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="phone" id="phoneId" tabindex="90"
                       value="{if isset($biller.phone)}{$biller.phone|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="faxId" class="cols__1-span-2">{$LANG.fax}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="fax" id="faxId" tabindex="100"
                       value="{if isset($biller.fax)}{$biller.fax|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="emailId" class="cols__1-span-2">{$LANG.email}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="email" id="emailId" tabindex="110"
                       value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="signature" class="cols__1-span-2">{$LANG.signature}:
                <a class="cluetip" href="#" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpSignature"
                   title="{$LANG.signature}">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <div class="cols__3-span-8">
                <input name="signature" id="signature" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                <trix-editor class="margin__left-0-5" input="signature" tabindex="120"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
        <label for="paypalBusinessNameId" class="cols__1-span-2">{$LANG.paypalBusinessName}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="paypal_business_name" id="paypalBusinessNameId" tabindex="130"
                       value="{if isset($biller.paypal_business_name)}{$biller.paypal_business_name|htmlSafe}{/if}" size="25"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paypalNotifyUrlId" class="cols__1-span-2">{$LANG.paypalNotifyUrl}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="paypal_notify_url" id="paypalNotifyUrlId" tabindex="140"
                       value="{if isset($biller.paypal_notify_url)}{$biller.paypal_notify_url|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paypalReturnUrlId" class="cols__1-span-2">{$LANG.paypalReturnUrl}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="paypal_return_url" id="paypalReturnUrlId" tabindex="150"
                       value="{if isset($biller.paypal_return_url)}{$biller.paypal_return_url|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="ewayCustomerId" class="cols__1-span-2">{$LANG.ewayCustomerId}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="eway_customer_id" id="ewayCustomerId" tabindex="160"
                       value="{if isset($biller.eway_customer_id)}{$biller.eway_customer_id|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="paymentsGatewayApiId" class="cols__1-span-2">{$LANG.paymentsGatewayApiId}:</label>
            <div class="cols__3-span-8">
                <input type="text" class="margin__left-0-5" name="paymentsgateway_api_id" id="paymentsGatewayApiId" tabindex="170"
                       value="{if isset($biller.paymentsgateway_api_id)}{$biller.paymentsgateway_api_id|htmlSafe}{/if}" size="50"/>
            </div>
        </div>
        {if !empty($customFieldLabel.biller_cf1)}
            <div class="grid__container grid__head-10">
                <label for="customField1Id" class="cols__1-span-2">{$customFieldLabel.biller_cf1|htmlSafe}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields"
                       title="{$LANG.customFields}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" class="margin__left-0-5" name="custom_field1" id="customField1Id" tabindex="180"
                           value="{if isset($biller.custom_field1)}{$biller.custom_field1|htmlSafe}{/if}" size="50">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf2)}
            <div class="grid__container grid__head-10">
                <label for="customField2Id" class="cols__1-span-2">{$customFieldLabel.biller_cf2|htmlSafe}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields"
                       title="{$LANG.customFields}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" class="margin__left-0-5" name="custom_field2" id="customField2Id" tabindex="190"
                           value="{if isset($biller.custom_field2)}{$biller.custom_field2}{/if}" size="50">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf3)}
            <div class="grid__container grid__head-10">
                <label for="customField3Id" class="cols__1-span-2">{$customFieldLabel.biller_cf3|htmlSafe}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields"
                       title="{$LANG.customFields|htmlSafe}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" class="margin__left-0-5" name="custom_field3" id="customField3Id" tabindex="200"
                           value="{if isset($biller.custom_field3)}{$biller.custom_field3|htmlSafe}{/if}" size="50">
                </div>
            </div>
        {/if}
        {if !empty($customFieldLabel.biller_cf4)}
            <div class="grid__container grid__head-10">
                <label for="customField4Id" class="cols__1-span-2">{$customFieldLabel.biller_cf4|htmlSafe}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields"
                       title="{$LANG.customFields}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <div class="cols__3-span-8">
                    <input type="text" class="margin__left-0-5" name="custom_field4" id="customField4Id" tabindex="210"
                           value="{if isset($biller.custom_field4)}{$biller.custom_field4|htmlSafe}{/if}" size="50">
                </div>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="logoId" class="cols__1-span-2">{$LANG.logoFile}:
                <a class="cluetip" href="#" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInsertBillerText"
                   title="{$LANG.logoFile}">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <div class="cols__3-span-8">
                {html_options name=logo id=logoId class=margin__left-0-5 output=$files values=$files selected=$biller.logo tabindex=220}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="footer" class="cols__1-span-2">{$LANG.invoiceFooter}:</label>
            <div class="cols__3-span-8">
                <input name="footer" id="footer" {if isset($biller.footer)}value="{$biller.footer|outHtml}"{/if} type="hidden">
                <trix-editor class="margin__left-0-5" input="footer" tabindex="230"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="notes" class="cols__1-span-2">{$LANG.notes}:</label>
            <div class="cols__3-span-8">
                <input name="notes" id="notes" {if isset($biller.notes)}value="{$biller.notes|outHtml}"{/if} type="hidden">
                <trix-editor class="margin__left-0-5" input="notes" tabindex="240"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__1-span-2">{$LANG.enabled}:</label>
            <div class="cols__3-span-8">
                {html_options name=enabled id=enabledId class=margin__left-0-5 options=$enabled selected=$biller.enabled tabindex=250}
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
