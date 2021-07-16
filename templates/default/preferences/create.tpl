{if !empty($smarty.post.p_description)}
    {include file="templates/default/preferences/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=preferences&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="descriptionId" class="cols__2-span-3">{$LANG.descriptionUc}:
                    <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_description" id="descriptionId" class="cols__5-span-6 validate[required]" tabindex="10"
                       value="{if isset($smarty.post.p_description)}{$smarty.post.p_description|htmlSafe}{/if}" size="25"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="currencySignId" class="cols__2-span-3">{$LANG.currencySign}:
                    <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_currency_sign" id="currencySignId" class="cols__5-span-2" size="15" tabindex="20"
                       value="{if isset($smarty.post.p_currency_sign)}{$smarty.post.p_currency_sign|htmlSafe}{/if}"/>
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
                <input type="text" name="currency_code" id="currencyCodeId" class="cols__5-span-2" tabindex="30"
                       value="{if isset($smarty.post.currency_code)}{$smarty.post.currency_code|htmlSafe}{/if}" size="15"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invHeadingId" class="cols__2-span-3">{$LANG.invoiceHeading}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceHeading}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_heading" id="invHeadingId" class="cols__5-span-6" tabindex="40"
                       value="{if isset($smarty.post.p_inv_heading)}{$smarty.post.p_inv_heading|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invWordingId" class="cols__2-span-3">{$LANG.invoiceWording}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceWording}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_wording" id="invWordingId" class="cols__5-span-6" tabindex="50"
                       value="{if isset($smarty.post.p_inv_wording)}{$smarty.post.p_inv_wording|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invDetailHeadingId" class="cols__2-span-3">{$LANG.invoiceDetailHeading}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_detail_heading" id="invDetailHeadingId" class="cols__5-span-6" tabindex="60"
                       value="{if isset($smarty.post.p_inv_detail_heading)}{$smarty.post.p_inv_detail_heading|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invDetailLineId" class="cols__2-span-3">{$LANG.invoiceDetailLine}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_detail_line" id="invDetailLineId" class="cols__5-span-6" tabindex="70"
                       value="{if isset($smarty.post.p_inv_detail_line)}{$smarty.post.p_inv_detail_line|htmlSafe}{/if}" size="75"/>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-3 bold margin__top-0-5">{$LANG.includeOnlinePayment}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                        <img src="{$helpImagePath}help-small.png" alt="{$LANG.invoiceDetailLine}"/>
                    </a>
                </div>
                <div class="cols__5-span-6 margin__left-0-5">
                    <div class="grid__container grid__head-2">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1"
                               value='paypal' tabindex="80"><span class="cols__2-span-1">{$LANG.paypal}</span>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1"
                               value='eway_merchant_xml' tabindex="81"><span class="cols__2-span-2">{$LANG.ewayMerchantXml}</span>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="checkbox" name="include_online_payment[]" class="cols__1-span-1"
                               value='paymentsgateway' tabindex="82"><span class="cols__2-span-2">{$LANG.paymentsGateway}</span>
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
                <input type="text" name="p_inv_payment_method" id="invPaymentMethodId" class="cols__5-span-6" tabindex="90"
                       value="{if isset($smarty.post.p_inv_payment_method)}{$smarty.post.p_inv_payment_method|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invPaymentLine1NameId" class="cols__2-span-3">{$LANG.invoicePaymentLine1Name}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_payment_line1_name" id="invPaymentLine1NameId" class="cols__5-span-6" tabindex="100"
                       value="{if isset($smarty.post.p_inv_payment_line1_name)}{$smarty.post.p_inv_payment_line1_name|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invPaymentLine1ValueId" class="cols__2-span-3">{$LANG.invoicePaymentLine1Value}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_payment_line1_value" id="invPaymentLine1ValueId" class="cols__5-span-6" tabindex="110"
                       value="{if isset($smarty.post.p_inv_payment_line1_value)}{$smarty.post.p_inv_payment_line1_value|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invPaymentLine2NameId" class="cols__2-span-3">{$LANG.invoicePaymentLine2Name}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_payment_line2_name" id="invPaymentLine2NameId" class="cols__5-span-6" tabindex="120"
                       value="{if isset($smarty.post.p_inv_payment_line2_name)}{$smarty.post.p_inv_payment_line2_name|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invPaymentLine2ValueId" class="cols__2-span-3">{$LANG.invoicePaymentLine2Value}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="p_inv_payment_line2_value" id="invPaymentLine2ValueId" class="cols__5-span-6" tabindex="130"
                       value="{if isset($smarty.post.p_inv_payment_line2_value)}{$smarty.post.p_inv_payment_line2_value|htmlSafe}{/if}" size="50"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="statusId" class="cols__2-span-3">{$LANG.status}:
                    <a class="cluetip" href="#" title="{$LANG.status}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <select name="status" id="statusId" class="cols__5-span-1" tabindex="140">
                    <option value="1" selected>{$LANG.real}</option>
                    <option value="0">{$LANG.draft}</option>
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="groupId" class="cols__2-span-3">{$LANG.invoiceNumberingGroup}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                {if !isset($indexInfo) }
                    <p class="cols__5-span-6"><em>{$LANG.noPreferences}</em></p>
                {else}
                    <select name="index_group" class="cols__5-span-2" id="groupId" tabindex="150" onchange="checkIndexGroup()">
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
                    <label for="nextIndexId" id="nextIndexIdLabel" class="cols__7-span-2 bold margin__left-1" style="display:none">{$LANG.startingNumber}:
                        <a class="cluetip" href="#" title="{$LANG.startingNumber}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpStartingNumber">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <input type="text" class="cols__9-span-2 margin__top-0-5" name="nextIndexId" id="nextIndexId" tabindex="155" style="display:none;">
                {/if}

            </div>
            <div class="grid__container grid__head-10">
                <label for="setAgingId" class="cols__2-span-3">{$LANG.setAging}:
                    <a class="cluetip" href="#" title="{$LANG.setAging}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <select name="set_aging" id="setAgingId" class="cols__5-span-2" tabindex="160">
                    <option value="{$smarty.const.DISABLED}" selected>{$LANG.disabled}</option>
                    <option value="{$smarty.const.ENABLED}">{$LANG.enabled}</option>
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="languageId" class="cols__2-span-3">{$LANG.language}:
                    <a class="cluetip" href="#" title="{$LANG.language}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                        <img src="{$helpImagePath}help-small.png" alt=""/></a>
                </label>
                <select name="language" id="languageId" class="cols__5-span-2" tabindex="170">
                    {foreach $localeList as $locale}
                        {* There is no config default for language so set same default a local. *}
                        <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="localeId" class="cols__2-span-3">{$LANG.locale}:
                    <a class="cluetip" href="#" title="{$LANG.locale}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                        <img src="{$helpImagePath}help-small.png" alt=""/></a>
                </label>
                <select name="locale" id="localeId" class="cols__5-span-2" tabindex="175">
                    {foreach $localeList as $locale}
                        <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__2-span-3">{$LANG.enabled}:
                    <a class="cluetip" href="#" title="{$LANG.enabled}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </label>
                <select name="pref_enabled" id="enabledId" class="cols__5-span-2" tabindex="180">
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
