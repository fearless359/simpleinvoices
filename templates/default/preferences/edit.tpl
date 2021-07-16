<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=preferences&amp;view=save&amp;id={$smarty.get.id}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="descId" class="cols__2-span-3">{$LANG.descriptionUc}:
                <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                    <img src="{$helpImagePath}required-small.png" alt=""/>
                </a>
                <a class="cluetip" href="#" title="{$LANG.descriptionUc}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_description' id="descId" class="cols__5-span-5 validate[required]"
                   size="50" tabindex="10"
                   value="{if isset($preference.pref_description)}{$preference.pref_description|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="currencySignId" class="cols__2-span-3">{$LANG.currencySign}:
                <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_currency_sign' id="currencySignId" class="cols__5-span-2" size="15" tabindex="20"
                   value="{if isset($preference.pref_currency_sign)}{$preference.pref_currency_sign}{/if}"/>
            <a class="cols__7-span-4 margin__left-1 margin__top-0-5 cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                {$LANG.currencySignNonDollar}
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="grid__container grid__head-10">
            <label for="currencyCodeId" class="cols__2-span-3">{$LANG.currencyCode}:
                <a class="cluetip" href="#" title="{$LANG.currencyCode}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpCurrencyCode">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='currency_code' id="currencyCodeId" class="cols__5-span-5" size="15" tabindex="30"
                   value="{if isset($preference.currency_code)}{$preference.currency_code}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invHeadingId" class="cols__2-span-3">{$LANG.invoiceHeading}:
                <a class="cluetip" href="#" title="{$LANG.invoiceHeading}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_heading' id="invHeadingId" class="cols__5-span-5" size="50" tabindex="40"
                   value="{if isset($preference.pref_inv_heading)}{$preference.pref_inv_heading|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invWordingId" class="cols__2-span-3">{$LANG.invoiceWording}:
                <a class="cluetip" href="#" title="{$LANG.invoiceWording}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_wording' id="invWordingId" class="cols__5-span-5" size="50" tabindex="50"
                   value="{if isset($preference.pref_inv_wording)}{$preference.pref_inv_wording|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invDetailHeadingId" class="cols__2-span-3">{$LANG.invoiceDetailHeading}:
                <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_detail_heading' id="invDetailHeadingId" class="cols__5-span-5"
                   size="50" tabindex="60"
                   value="{if isset($preference.pref_inv_detail_heading)}{$preference.pref_inv_detail_heading|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invDetailLineId" class="cols__2-span-3">{$LANG.invoiceDetailLine}:
                <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                    <img src="{$helpImagePath}help-small.png" alt=""/></a>
            </label>
            <input type="text" name='pref_inv_detail_line' id="invDetailLineId" class="cols__5-span-5"
                   size="75" tabindex="70"
                   value="{if isset($preference.pref_inv_detail_line)}{$preference.pref_inv_detail_line|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-3 bold margin__top-0-5">{$LANG.includeOnlinePayment}:
                <a class="cluetip" href="#" title="{$LANG.includeOnlinePayment}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </div>
            <div class="cols__5-span-6">
                <div class="grid__container grid__head-2">
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt1Id"
                           class="cols__1-span-1 margin__top-0-5" value='paypal' tabindex="80"
                           {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt1Id" class="cols__2-span-1 margin__top-0-2">{$LANG.paypal}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt2Id"
                           class="cols__1-span-1 margin__top-0-5" tabindex="81" value='eway_merchant_xml'
                           {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt2Id" class="cols__2-span-1 margin__top-0-2">{$LANG.ewayMerchantXml}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt3Id"
                           class="cols__1-span-1 margin__top-0-5" tabindex="82" value='paymentsgateway'
                           {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt3Id" class="cols__2-span-1 margin__top-0-2">{$LANG.paymentsGateway}</label>
                </div>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invPaymentMethodId" class="cols__2-span-3">{$LANG.invoicePaymentMethod}:
                <a class="cluetip" href="#" title="{$LANG.invoicePaymentMethod}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoicePaymentMethod">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_payment_method' id="invPaymentMethodId"
                   class="cols__5-span-5" size="50" tabindex="90"
                   value="{if isset($preference.pref_inv_payment_method)}{$preference.pref_inv_payment_method|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invPaymentLine1NameId" class="cols__2-span-3">{$LANG.invoicePaymentLine1Name}:
                <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_payment_line1_name' id="invPaymentLine1NameId"
                   class="cols__5-span-5" size="50" tabindex="100"
                   value="{if isset($preference.pref_inv_payment_line1_name)}{$preference.pref_inv_payment_line1_name|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invPaymentLine1ValueId" class="cols__2-span-3">{$LANG.invoicePaymentLine1Value}:
                <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_payment_line1_value' id="invPaymentLine1ValueId"
                   class="cols__5-span-5" size="50" tabindex="110"
                   value="{if isset($preference.pref_inv_payment_line1_value)}{$preference.pref_inv_payment_line1_value|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invPaymentLine2NameId" class="cols__2-span-3">{$LANG.invoicePaymentLine2Name}:
                <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_payment_line2_name' id="invPaymentLine2NameId"
                   class="cols__5-span-5" size="50" tabindex="120"
                   value="{if isset($preference.pref_inv_payment_line2_name)}{$preference.pref_inv_payment_line2_name|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invPaymentLine2ValueId" class="cols__2-span-3">{$LANG.invoicePaymentLine2Value}:
                <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name='pref_inv_payment_line2_value' id="invPaymentLine2ValueId"
                   class="cols__5-span-5" size="50" tabindex="130"
                   value="{if isset($preference.pref_inv_payment_line2_value)}{$preference.pref_inv_payment_line2_value|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="statusId" class="cols__2-span-3">{$LANG.status}:
                <a class="cluetip" href="#" title="{$LANG.status}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="status" id="statusId" class="cols__5-span-5" tabindex="140">
                {foreach $status as $s}
                    <option {if $s.id == $preference.status} selected {/if} value="{if isset($s.id)}{$s.id}{/if}">{$s.status}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="groupId" class="cols__2-span-3">{$LANG.invoiceNumberingGroup}:
                <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="index_group" id="groupId" class="cols__5-span-2" tabindex="150" onchange="checkIndexGroup()">
                {if $useThisPref}
                    <option value="0">{$LANG.useThisPref}</option>
                {/if}
                {foreach $indexInfo as $ii}
                    <option {if $ii.pref_id == $preference.index_group} selected {/if}
                            value="{$ii.pref_id|htmlSafe}">{$ii.pref_description|htmlSafe}
                    </option>
                {/foreach}
            </select>
            <script>
                {literal}
                function checkIndexGroup() {
                    let nextIndexIdLabelElem = document.getElementById('nextIndexIdLabel'),
                        nextIndexIdElem = document.getElementById('nextIndexId'),
                        startingIndexIdLabelElem = document.getElementById('startingIndexIdLabel'),
                        startingIndexIdElem = document.getElementById('startingIndexId');
                    if (document.getElementById('groupId').value === "0") {
                        nextIndexIdLabelElem.style.display = "none";
                        nextIndexIdElem.style.display = "none";
                        startingIndexIdLabelElem.style.display = "inline";
                        startingIndexIdElem.style.display = "inline";
                    } else {
                        nextIndexIdLabelElem.style.display = "inline";
                        nextIndexIdElem.style.display = "inline";
                        startingIndexIdLabelElem.style.display = "none";
                        startingIndexIdElem.style.display = "none";
                    }
                }
                {/literal}
            </script>
            <label for="startingIndexId" class="cols__7-span-2 bold margin__left-1" id="startingIndexIdLabel" style="display:none">{$LANG.startingNumber}:
                <a class="cluetip" href="#" title="{$LANG.startingNumber}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpStartingNumber">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="startingIndexId" id="startingIndexId" class="cols__9-span-2" tabindex="155"
                   value="{$startingId}" style="display:none;">
            <span class="cols__7-span-2 bold margin__top-0-5 margin__left-1" id="nextIndexIdLabel" style="display:inline">{$LANG.nextNumber}: </span>
            <span id="nextIndexId" class="cols__9-span-2 margin__top-0-5" style="display:inline">{$nextId}</span>
        </div>
        <div class="grid__container grid__head-10">
            <label for="setAgingId" class="cols__2-span-3">{$LANG.setAging}:
                <a class="cluetip" href="#" title="{$LANG.setAging}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="set_aging" id="setAgingId" class="cols__5-span-5" tabindex="160">
                <option value="{$smarty.const.ENABLED}" {if $preference.set_aging == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                <option value="{$smarty.const.DISABLED}" {if $preference.set_aging != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__2-span-3">{$LANG.enabled}:
                <a class="cluetip" href="#" title="{$LANG.enabled}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="pref_enabled" id="enabledId" class="cols__5-span-5" tabindex="170">
                <option value="{$smarty.const.ENABLED}" {if $preference.pref_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                <option value="{$smarty.const.DISABLED}" {if $preference.pref_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="languageId" class="cols__2-span-3">{$LANG.language}:
                <a class="cluetip" href="#" title="{$LANG.language}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="language" id="languageId" class="cols__5-span-5" tabindex="180">
                {foreach $localeList as $locale}
                    <option value="{$locale|htmlSafe}" {if $locale == $preference.language}selected{/if}>{$locale|htmlSafe}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="localeId" class="cols__2-span-3">{$LANG.locale}:
                <a class="cluetip" href="#" title="{$LANG.locale}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <select name="locale" id="localeId" class="cols__5-span-5" tabindex="190">
                {foreach $localeList as $locale}
                    <option value="{$locale|htmlSafe}" {if $locale == $preference.locale}selected{/if}>{$locale|htmlSafe}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_preference" value="{$LANG.save}" tabindex="200">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>

        <a href="index.php?module=preferences&amp;view=manage" class="button negative" tabindex="210">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>

    <div class="si_help_div">
        <a class="cluetip" href="#" title="{$LANG.whatsAllThisInvPref}" tabindex="-1"
           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefWhatThe">
            <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
