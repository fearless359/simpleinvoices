{*
 *  Script: view.tpl
 *      Preferences details template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.descriptionUc}:</div>
        <div>{$preference.pref_description}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.currencySign}:</div>
        <div>{$preference.pref_currency_sign}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.currencyCode}:</div>
        <div>{$preference.currency_code|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceHeading}:</div>
        <div>{$preference.pref_inv_heading|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceWording}:</div>
        <div>{$preference.pref_inv_wording|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceDetailHeading}:</div>
        <div>{$preference.pref_inv_detail_heading|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1 margin__top-3">{$LANG.includeOnlinePayment}:</div>
        <div class="grid__container grid__head-checkbox margin__left-0">
            <input type="checkbox" name="include_online_payment[]" id="olPymt1Id" value="paypal" disabled
                   {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
            <label for="olPymt1Id" class="margin__left-1-5 margin__top-0">{$LANG.paypal}</label>
            <input type="checkbox" name="include_online_payment[]" id="olPymt2Id" value='eway_merchant_xml' disabled
                   class="margin__top-0-5"
                   {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
            <label for="olPymt2Id" class="margin__left-1-5 margin__top-0-5">{$LANG.ewayMerchantXml}</label>
            <input type="checkbox" name="include_online_payment[]" id="olPymt3Id" value='paymentsgateway' disabled
                   class="margin__top-0-5"
                   {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
            <label for="olPymt3Id" class="margin__left-1-5 margin__top-0-5">{$LANG.paymentsGateway}</label>
        </div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoicePaymentMethod}:</div>
        <div>{$preference.pref_inv_payment_method|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoicePaymentLine1Name}:</div>
        <div>{$preference.pref_inv_payment_line1_name|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoicePaymentLine1Value}:</div>
        <div>{$preference.pref_inv_payment_line1_value|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoicePaymentLine2Name}:</div>
        <div>{$preference.pref_inv_payment_line2_name|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoicePaymentLine2Value}:</div>
        <div>{$preference.pref_inv_payment_line2_value|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$preference.enabled_text}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.status}:</div>
        <div>{$preference.status_wording}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceNumberingGroup}:</div>
        <div>
            {$indexGroup.pref_description}&nbsp;(<span class="bold margin__right-1">{$LANG.nextNumber}:</span>{$nextId})
        </div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.setAging}:</div>
        <div>{$preference.set_aging_text}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.language}:</div>
        <div>{$preference.language}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.locale}:</div>
        <div>{$preference.locale}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=preferences&amp;view=edit&amp;id={$preference.pref_id}" class="button positive">
        <img src="images/report_edit.png" alt=""/>{$LANG.edit}</a>

    <a href="index.php?module=preferences&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt=""/>{$LANG.cancel}</a>
</div>
<div class="si__help-div">
    <a class="tooltip" title="{$LANG.helpInvPrefWhatThe}">
        <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
    </a>
</div>
