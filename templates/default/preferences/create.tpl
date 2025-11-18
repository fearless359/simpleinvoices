{*
 *  Script: create.tpl
 *      Invoice Preferences add template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210619 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
{if !empty($smarty.post.p_description)}
    {include file="templates/default/preferences/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=preferences&amp;view=create">
        <div class="flex__area">
            <div class="flex__container flex__start">
                <label for="descriptionId" class="margin__right-1">{$LANG.descriptionUc}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpInvPrefDescription}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="p_description" id="descriptionId" required tabindex="10"
                       value="{if isset($smarty.post.p_description)}{$smarty.post.p_description|htmlSafe}{/if}" size="25"/>
            </div>
            <div class="flex__container flex__start">
                <label for="currencySignId" class="margin__right-1">{$LANG.currencySign}:
                    <img class="tooltip" title="{$LANG.helpInvPrefCurrencySign}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_currency_sign" id="currencySignId" size="15" tabindex="20"
                       value="{if isset($smarty.post.p_currency_sign)}{$smarty.post.p_currency_sign|htmlSafe}{/if}"/>
            </div>
            <div class="flex__container flex__start">
                <label for="currencyCodeId" class="margin__right-1">{$LANG.currencyCode}:
                    <img class="tooltip" title="{$LANG.helpCurrencyCode}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="currency_code" id="currencyCodeId" tabindex="30"
                       value="{if isset($smarty.post.currency_code)}{$smarty.post.currency_code|htmlSafe}{/if}" size="15"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invHeadingId" class="margin__right-1">{$LANG.invoiceHeading}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceHeading}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_heading" id="invHeadingId" tabindex="40"
                       value="{if isset($smarty.post.p_inv_heading)}{$smarty.post.p_inv_heading|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invWordingId" class="margin__right-1">{$LANG.invoiceWording}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceWording}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_wording" id="invWordingId" tabindex="50"
                       value="{if isset($smarty.post.p_inv_wording)}{$smarty.post.p_inv_wording|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invDetailHeadingId" class="margin__right-1">{$LANG.invoiceDetailHeading}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailHeading}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_detail_heading" id="invDetailHeadingId" tabindex="60"
                       value="{if isset($smarty.post.p_inv_detail_heading)}{$smarty.post.p_inv_detail_heading|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invDetailLineId" class="margin__right-1">{$LANG.invoiceDetailLine}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailLine}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_detail_line" id="invDetailLineId" tabindex="70"
                       value="{if isset($smarty.post.p_inv_detail_line)}{$smarty.post.p_inv_detail_line|htmlSafe}{/if}" size="75"/>
            </div>
            <div class="flex__container flex__start">
                <div class="bold margin__right-1 margin__top-3">{$LANG.includeOnlinePayment}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceDetailLine}" src="{$helpImagePath}help-small.png" alt="{$LANG.invoiceDetailLine}"/>
                </div>
                <div class="grid__container grid__head-checkbox margin__left-0">
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt1Id"
                           class="cols__1-span-1 margin__top-0-5" value='paypal' tabindex="80">
                    <label for="onlinePymt1Id" class="cols__2-span-1 margin__top-0-2 margin__left-2">{$LANG.paypal}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt2Id"
                           class="cols__1-span-1 margin__top-0-5" tabindex="81" value='eway_merchant_xml'>
                    <label for="onlinePymt2Id" class="cols__2-span-1 margin__top-0-2 margin__left-2">{$LANG.ewayMerchantXml}</label>
                    <input type="checkbox" name="include_online_payment[]" id="onlinePymt3Id"
                           class="cols__1-span-1 margin__top-0-5" tabindex="82" value='paymentsgateway'>
                    <label for="onlinePymt3Id" class="cols__2-span-1 margin__top-0-2 margin__left-2">{$LANG.paymentsGateway}</label>
                </div>
            </div>
            <div class="flex__container flex__start">
                <label for="invPaymentMethodId" class="margin__right-1">{$LANG.invoicePaymentMethod}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoicePaymentMethod}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_payment_method" id="invPaymentMethodId" tabindex="90"
                       value="{if isset($smarty.post.p_inv_payment_method)}{$smarty.post.p_inv_payment_method|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invPaymentLine1NameId" class="margin__right-1">{$LANG.invoicePaymentLine1Name}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Name}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_payment_line1_name" id="invPaymentLine1NameId" tabindex="100"
                       value="{if isset($smarty.post.p_inv_payment_line1_name)}{$smarty.post.p_inv_payment_line1_name|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invPaymentLine1ValueId" class="margin__right-1">{$LANG.invoicePaymentLine1Value}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine1Value}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_payment_line1_value" id="invPaymentLine1ValueId" tabindex="110"
                       value="{if isset($smarty.post.p_inv_payment_line1_value)}{$smarty.post.p_inv_payment_line1_value|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invPaymentLine2NameId" class="margin__right-1">{$LANG.invoicePaymentLine2Name}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Name}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_payment_line2_name" id="invPaymentLine2NameId" tabindex="120"
                       value="{if isset($smarty.post.p_inv_payment_line2_name)}{$smarty.post.p_inv_payment_line2_name|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="invPaymentLine2ValueId" class="margin__right-1">{$LANG.invoicePaymentLine2Value}:
                    <img class="tooltip" title="{$LANG.helpInvPrefPaymentLine2Value}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="p_inv_payment_line2_value" id="invPaymentLine2ValueId" tabindex="130"
                       value="{if isset($smarty.post.p_inv_payment_line2_value)}{$smarty.post.p_inv_payment_line2_value|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="flex__container flex__start">
                <label for="statusId" class="margin__right-1">{$LANG.status}:
                    <img class="tooltip" title="{$LANG.helpInvPrefStatus}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <select name="status" id="statusId" tabindex="140">
                    <option value="1" selected>{$LANG.real}</option>
                    <option value="0">{$LANG.draft}</option>
                </select>
            </div>
            <div class="flex__container flex__start">
                <label for="groupId" class="margin__right-1">{$LANG.invoiceNumberingGroup}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceNumberingGroup}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                {if !isset($indexInfo) }
                    <p><em>{$LANG.noPreferences}</em></p>
                {else}
                    <select name="index_group" id="groupId" tabindex="150" onchange="checkIndexGroup()">
                        <option value="0">{$LANG.useThisPref}</option>
                        {foreach $indexInfo as $ii}
                            <option {if $ii.pref_id == $defaults.preference} selected {/if}
                                    value="{$ii.pref_id|htmlSafe}">{$ii.pref_description|htmlSafe}
                            </option>
                        {/foreach}
                    </select>
                    <script>
                        {literal}
                        function checkIndexGroup() {
                            let nextIndexIdLabelElem = document.getElementById('nextIndexIdLabel'),
                                nextIndexIdElem = document.getElementById('nextIndexId');
                            if (document.getElementById('groupId').value === "0") {
                                nextIndexIdLabelElem.style.display = "inline";
                                nextIndexIdElem.style.display = "inline";
                            } else {
                                nextIndexIdLabelElem.style.display = "none";
                                nextIndexIdElem.style.display = "none";
                            }
                        }
                        {/literal}
                    </script>
                    <label for="nextIndexId" id="nextIndexIdLabel" class="margin__left-1 margin__right-1"
                           style="display:none">{$LANG.startingNumber}:
                        <img class="tooltip" title="{$LANG.helpStartingNumber}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <input type="text" name="nextIndexId" id="nextIndexId" tabindex="155" style="display:none;">
                {/if}
            </div>
            <div class="flex__container flex__start">
                <label for="setAgingId" class="margin__right-1">{$LANG.setAging}:
                    <img class="tooltip" title="{$LANG.helpSetAging}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <select name="set_aging" id="setAgingId" tabindex="160">
                    <option value="{$smarty.const.DISABLED}" selected>{$LANG.disabled}</option>
                    <option value="{$smarty.const.ENABLED}">{$LANG.enabled}</option>
                </select>
            </div>
            <div class="flex__container flex__start">
                <label for="languageId" class="margin__right-1">{$LANG.language}:
                    <img class="tooltip" title="{$LANG.helpInvPrefLanguage}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <select name="language" id="languageId" tabindex="170">
                    {foreach $localeList as $locale}
                        {* There is no config default for language so set same default a local. *}
                        <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            <div class="flex__container flex__start">
                <label for="localeId" class="margin__right-1">{$LANG.locale}:
                    <img class="tooltip" title="{$LANG.helpInvPrefLocale}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <select name="locale" id="localeId" tabindex="175">
                    {foreach $localeList as $locale}
                        <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            <div class="flex__container flex__start">
                <label for="enabledId" class="margin__right-1">{$LANG.enabled}:
                    <img class="tooltip" title="{$LANG.helpInvPrefInvoiceEnabled}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <select name="pref_enabled" id="enabledId" tabindex="180">
                    <option value="{$smarty.const.DISABLED}">{$LANG.disabled}</option>
                    <option value="{$smarty.const.ENABLED}" selected>{$LANG.enabled}</option>
                </select>
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}" tabindex="190">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=preferences&amp;view=manage" class="button negative" tabindex="200">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
