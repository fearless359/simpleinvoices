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
            <a class="cluetip" href="#" title="{$LANG.descriptionUc}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_description}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.currencySign}:
            <a class="cluetip" href="#" title="{$LANG.currencySign}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_currency_sign}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.currencyCode}:
            <a class="cluetip" href="#" title="{$LANG.currencyCode}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpCurrencyCode">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.currency_code|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceHeading}:
            <a class="cluetip" href="#" title="{$LANG.invoiceHeading}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceWording}:
            <a class="cluetip" title="{$LANG.invoiceWording}"
               href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_wording|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceDetailHeading}:
            <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_detail_heading|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.includeOnlinePayment}:
            <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">
            <div class="grid__container grid__head-2">
                <input type=checkbox name=include_online_payment[] id="olPymt1Id" value='paypal' disabled
                       {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt1Id" class="margin__top-0">{$LANG.paypal}</label>
                <input type=checkbox name=include_online_payment[] id="olPymt2Id" value='eway_merchant_xml' disabled
                       {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt2Id" class="margin__top-0">{$LANG.ewayMerchantXml}</label>
                <input type=checkbox name=include_online_payment[] id="olPymt3Id" value='paymentsgateway' disabled
                       {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
                <label for="olPymt3Id" class="margin__top-0">{$LANG.paymentsGateway}</label>
            </div>
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentMethod}:
            <a class="cluetip" href="#" title="{$LANG.invoicePaymentMethod}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoicePaymentMethod">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_method|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine1Name}:
            <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine1Value}:
            <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line1_value|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine2Name}:
            <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoicePaymentLine2Value}:
            <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.pref_inv_payment_line2_value|htmlSafe}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.enabled}:
            <a class="cluetip" href="#" title="{$LANG.enabled}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.enabled_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.status}:
            <a class="cluetip" href="#" title="{$LANG.status}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.status_wording}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceNumberingGroup}:
            <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">
            {$indexGroup.pref_description}&nbsp;&nbsp;
                                          (<span class="bold;color:#777">{$LANG.nextNumber}: </span>
            {$nextId})
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.setAging}:
            <a class="cluetip" href="#" title="{$LANG.setAging}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.set_aging_text}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.language}:
            <a class="cluetip" href="#" title="{$LANG.language}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-5">{$preference.language}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.locale}:
            <a class="cluetip" href="#" title="{$LANG.locale}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
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
    <a class="cluetip" href="#" title="{$LANG.whatsAllThisInvPref}"
       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefWhatThe">
        <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
    </a>
</div>
