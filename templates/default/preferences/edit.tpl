{*
 *  Script: view.tpl
 *      Preferences details template
 *
 *  Last edited:
 *      20251209 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210630 by Rich Rowley to convert to grid layout.
 *      20090502 by Gates to display apostrophes in text box output.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=preferences&amp;view=save&amp;id={$smarty.get.id}">
        <div class="row">
            <div class="col__25">
                <label for="descId">{$LANG.descriptionUc}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpInvPrefDescription}"
                         src="{$helpImagePath}required-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_description' id="descId" required size="50" tabindex="10"
                       value="{if isset($preference.pref_description)}{$preference.pref_description|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="currencySignId">{$LANG.currencySign}:
                    <img class="tooltip" title="{$LANG.helpInvPrefCurrencySign}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_currency_sign' id="currencySignId" size="15" tabindex="20"
                       value="{if isset($preference.pref_currency_sign)}{$preference.pref_currency_sign}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="currencyCodeId">{$LANG.currencyCode}:
                    <img class="tooltip" title="{$LANG.helpCurrencyCode}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='currency_code' id="currencyCodeId" size="15" tabindex="30"
                       value="{if isset($preference.currency_code)}{$preference.currency_code}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invHeadingId">{$LANG.invoiceHeading}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceHeading}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_heading' id="invHeadingId" size="50" tabindex="40"
                       value="{if isset($preference.pref_inv_heading)}{$preference.pref_inv_heading|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invWordingId">{$LANG.invoiceWording}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceWording}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_wording' id="invWordingId" size="50" tabindex="50"
                       value="{if isset($preference.pref_inv_wording)}{$preference.pref_inv_wording|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invDetailHeadingId">{$LANG.invoiceDetailHeading}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailHeading}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_detail_heading' id="invDetailHeadingId" size="50" tabindex="60"
                       value="{if isset($preference.pref_inv_detail_heading)}{$preference.pref_inv_detail_heading|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invDetailLineId">{$LANG.invoiceDetailLine}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailLine}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_detail_line' id="invDetailLineId" size="75" tabindex="70"
                       value="{if isset($preference.pref_inv_detail_line)}{$preference.pref_inv_detail_line|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.includeOnlinePayment}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailLine}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </span>
            </div>
            <div class="col__75">
                <div class="grid__container grid__head-checkbox margin__left-0">
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt1Id"
                           class="cols__1-span-1" value="'paypal" tabindex="80"
                           {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt1Id" class="cols__2-span-1">{$LANG.paypal}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt2Id"
                           class="cols__1-span-1" tabindex="81" value='eway_merchant_xml'
                           {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt2Id" class="cols__2-span-1">{$LANG.ewayMerchantXml}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt3Id"
                           class="cols__1-span-1" tabindex="82" value='paymentsgateway'
                           {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}>
                    <label for="onlinePymt3Id" class="cols__2-span-1-5">{$LANG.paymentsGateway}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invPaymentMethodId">{$LANG.invoicePaymentMethod}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoicePaymentMethod}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_payment_method' id="invPaymentMethodId" size="50" tabindex="90"
                       value="{if isset($preference.pref_inv_payment_method)}{$preference.pref_inv_payment_method|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invPaymentLine1NameId">{$LANG.invoicePaymentLine1Name}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Name}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_payment_line1_name' id="invPaymentLine1NameId" size="50"
                       tabindex="100"
                       value="{if isset($preference.pref_inv_payment_line1_name)}{$preference.pref_inv_payment_line1_name|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invPaymentLine1ValueId">{$LANG.invoicePaymentLine1Value}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Value}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_payment_line1_value' id="invPaymentLine1ValueId" size="50"
                       tabindex="110"
                       value="{if isset($preference.pref_inv_payment_line1_value)}{$preference.pref_inv_payment_line1_value|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invPaymentLine2NameId">{$LANG.invoicePaymentLine2Name}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Name}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_payment_line2_name' id="invPaymentLine2NameId" size="50"
                       tabindex="120"
                       value="{if isset($preference.pref_inv_payment_line2_name)}{$preference.pref_inv_payment_line2_name|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="invPaymentLine2ValueId">{$LANG.invoicePaymentLine2Value}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Value}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <input type="text" name='pref_inv_payment_line2_value' id="invPaymentLine2ValueId" size="50"
                       tabindex="130"
                       value="{if isset($preference.pref_inv_payment_line2_value)}{$preference.pref_inv_payment_line2_value|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="statusId">{$LANG.status}:
                    <img class="tooltip" title="{$LANG.helpInvPrefStatus}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="status" id="statusId" tabindex="140">
                    {foreach $status as $s}
                        <option {if $s.id == $preference.status} selected {/if}
                                value="{if isset($s.id)}{$s.id}{/if}">{$s.status}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="groupId">{$LANG.invoiceNumberingGroup}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceNumberingGroup}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="index_group" id="groupId" tabindex="150" onchange="checkIndexGroup()">
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
                <div class="row">
                    <div class="col__30">
                        <label for="startingIndexId" class="margin__left-1 margin__right-1"
                               id="startingIndexIdLabel">{$LANG.startingNumber}:
                            <img class="tooltip" title="{$LANG.helpStartingNumber}" src="{$helpImagePath}help-small.png"
                                 alt=""/>
                        </label>
                    </div>
                    <div class="col__20">
                        <input type="text" name="startingIndexId" id="startingIndexId" tabindex="155"
                               value="{$startingId}">
                    </div>
                    <div class="col__25">
                        <span class="label margin__left-1" id="nextIndexIdLabel">{$LANG.nextNumber}:</span>
                    </div>
                    <div class="col__25 align__text-left" style="margin-top: 2rem;">
                        <span id="nextIndexId">{$nextId}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="setAgingId">{$LANG.setAging}:
                    <img class="tooltip" title="{$LANG.helpSetAging}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="set_aging" id="setAgingId" tabindex="160">
                    <option value="{$smarty.const.ENABLED}"
                            {if $preference.set_aging == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                    <option value="{$smarty.const.DISABLED}"
                            {if $preference.set_aging != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="enabledId">{$LANG.enabled}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceEnabled}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="pref_enabled" id="enabledId" tabindex="170">
                    <option value="{$smarty.const.ENABLED}"
                            {if $preference.pref_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                    <option value="{$smarty.const.DISABLED}"
                            {if $preference.pref_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="languageId">{$LANG.language}:
                    <img class="tooltip" title="{$LANG.helpInvPrefLanguage}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="language" id="languageId" tabindex="180">
                    {foreach $localeList as $locale}
                        <option value="{$locale|htmlSafe}"
                                {if $locale == $preference.language}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <label for="localeId">{$LANG.locale}:
                    <img class="tooltip" title="{$LANG.helpInvPrefLocale}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__75">
                <select name="locale" id="localeId" tabindex="190">
                    {foreach $localeList as $locale}
                        <option value="{$locale|htmlSafe}"
                                {if $locale == $preference.locale}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="positive" name="save_preference" value="{$LANG.save}" tabindex="200">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=preferences&amp;view=manage" class="button negative" tabindex="210">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <div class="si__help-div">
            <span class="tooltip" title="{$LANG.helpInvPrefWhatThe}">
                <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
            </span>
        </div>
        <input type="hidden" name="op" value="edit"/>
    </form>
</div>
