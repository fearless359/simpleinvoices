{*
 *  Script: view.tpl
 *      Preferences details template
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.descriptionUc}:</div>
        <div class="cols__5-span-5">{$preference.pref_description}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.currencySign}:</div>
        <div class="cols__5-span-5">{$preference.pref_currency_sign}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.currencyCode}:</div>
        <div class="cols__5-span-5">{$preference.currency_code|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoiceHeading}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoiceWording}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_wording|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoiceDetailHeading}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_detail_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.includeOnlinePayment}:</div>
        <div class="cols__5-span-3">
            <div class="grid__container grid__head-checkbox">
                <input type="checkbox" name="include_online_payment[]" id="olPymt1Id" value="paypal" disabled
                       class="margin__top-0-5"
                       {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt1Id" class="margin__left-0-5 margin__top-0">{$LANG.paypal}</label>
                <input type="checkbox" name="include_online_payment[]" id="olPymt2Id" value='eway_merchant_xml' disabled
                       class="margin__top-0-5"
                       {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt2Id" class="margin__left-0-5 margin__top-0">{$LANG.ewayMerchantXml}</label>
                <input type="checkbox" name="include_online_payment[]" id="olPymt3Id" value='paymentsgateway' disabled
                       class="margin__top-0-5"
                       {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt3Id" class="margin__left-0-5 margin__top-0">{$LANG.paymentsGateway}</label>
            </div>
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoicePaymentMethod}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_method|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoicePaymentLine1Name}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoicePaymentLine1Value}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_value|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoicePaymentLine2Name}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoicePaymentLine2Value}:</div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_value|htmlSafe}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.enabled}:</div>
        <div class="cols__5-span-5">{$preference.enabled_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.status}:</div>
        <div class="cols__5-span-5">{$preference.status_wording}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.invoiceNumberingGroup}:</div>
        <div class="cols__5-span-5">
            {$indexGroup.pref_description}&nbsp;&nbsp;(<span class="bold align__text-right margin__right-1;color:#777">{$LANG.nextNumber}:&nbsp;</span>{$nextId})
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.setAging}:</div>
        <div class="cols__5-span-5">{$preference.set_aging_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.language}:</div>
        <div class="cols__5-span-5">{$preference.language}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold align__text-right margin__right-1">{$LANG.locale}:</div>
        <div class="cols__5-span-5">{$preference.locale}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=preferences&amp;view=edit&amp;id={$preference.pref_id}" class="button positive">
        <img src="images/report_edit.png" alt=""/>{$LANG.edit}</a>

    <a href="index.php?module=preferences&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt=""/>{$LANG.cancel}</a>
</div>
<div class="si_help_div">
    <a class="tooltip" title="{$LANG.helpInvPrefWhatThe}">
        <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
    </a>
</div>
