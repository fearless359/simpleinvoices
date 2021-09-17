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
        <div class="cols__2-span-3 bold">{$LANG.descriptionUc}:
            <img class="tooltip" title="{$LANG.helpInvPrefDescription}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_description}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.currencySign}:
            <img class="tooltip" title="{$LANG.helpInvPrefCurrencySign}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_currency_sign}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.currencyCode}:
            <img class="tooltip" title="{$LANG.helpCurrencyCode}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.currency_code|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceHeading}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceHeading}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceWording}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceWording}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_wording|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceDetailHeading}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailHeading}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_detail_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.includeOnlinePayment}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailLine}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-3">
            <div class="grid__container grid__head-2">
                <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1" id="olPymt1Id" value="paypal" disabled
                       {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt1Id" class="cols__2-span-1 margin__top-0">{$LANG.paypal}</label>
                <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1" id="olPymt2Id" value='eway_merchant_xml' disabled
                       {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt2Id" class="cols__2-span-1 margin__top-0">{$LANG.ewayMerchantXml}</label>
                <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1" id="olPymt3Id" value='paymentsgateway' disabled
                       {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt3Id" class="cols__2-span-1 margin__top-0">{$LANG.paymentsGateway}</label>
            </div>
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentMethod}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoicePaymentMethod}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_method|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine1Name}:
            <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Name}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine1Value}:
            <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Value}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_value|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine2Name}:
            <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Name}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine2Value}:
            <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Value}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_value|htmlSafe}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.enabled}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceEnabled}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.enabled_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.status}:
            <img class="tooltip" title="{$LANG.helpInvPrefStatus}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.status_wording}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceNumberingGroup}:
            <img class="tooltip" title="{$LANG.helpInvPrefInvoiceNumberingGroup}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">
            {$indexGroup.pref_description}&nbsp;&nbsp;(<span class="bold;color:#777">{$LANG.nextNumber}:&nbsp;</span>{$nextId})
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.setAging}:
            <img class="tooltip" title="{$LANG.helpSetAging}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.set_aging_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.language}:
            <img class="tooltip" title="{$LANG.helpInvPrefLanguage}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.language}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.locale}:
            <img class="tooltip" title="{$LANG.helpInvPrefLocale}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div class="cols__5-span-5">{$preference.locale}</div>
    </div>
</div>
<div class="align__text-center margin__top-2">
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
