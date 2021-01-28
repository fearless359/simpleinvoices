{if !empty($smarty.post.p_description)}
    {include file="templates/default/preferences/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=preferences&amp;view=create">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.descriptionUc}:
                        <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" class="si_input validate[required]" name="p_description" tabindex="10"
                               value="{if isset($smarty.post.p_description)}{$smarty.post.p_description|htmlSafe}{/if}" size="25"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.currencySign}:
                        <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_currency_sign" class="si_input" tabindex="20"
                               value="{if isset($smarty.post.p_currency_sign)}{$smarty.post.p_currency_sign|htmlSafe}{/if}" size="15"/>
                        <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                            {$LANG.currencySignNonDollar}
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.currencyCode}:
                        <a class="cluetip" href="#" title="{$LANG.currencyCode}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCurrencyCode">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="currency_code" class="si_input" tabindex="30"
                               value="{if isset($smarty.post.currency_code)}{$smarty.post.currency_code|htmlSafe}{/if}" size="15"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoiceHeading}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceHeading}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_heading" class="si_input" tabindex="40"
                               value="{if isset($smarty.post.p_inv_heading)}{$smarty.post.p_inv_heading|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoiceWording}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceWording}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_wording" class="si_input" tabindex="50"
                               value="{if isset($smarty.post.p_inv_wording)}{$smarty.post.p_inv_wording|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoiceDetailHeading}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_detail_heading" class="si_input" tabindex="60"
                               value="{if isset($smarty.post.p_inv_detail_heading)}{$smarty.post.p_inv_detail_heading|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoiceDetailLine}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_detail_line" class="si_input" tabindex="70"
                               value="{if isset($smarty.post.p_inv_detail_line)}{$smarty.post.p_inv_detail_line|htmlSafe}{/if}" size="75"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.includeOnlinePayment}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type=checkbox name=include_online_payment[] class="si_input" value='paypal' tabindex="80">{$LANG.paypal}
                        <input type=checkbox name=include_online_payment[] class="si_input" value='eway_merchant_xml' tabindex="81">{$LANG.ewayMerchantXml}
                        <input type=checkbox name=include_online_payment[] class="si_input" value='paymentsgateway' tabindex="82">{$LANG.paymentsGateway}
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoicePaymentMethod}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentMethod}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoicePaymentMethod">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_payment_method" class="si_input" tabindex="90"
                               value="{if isset($smarty.post.p_inv_payment_method)}{$smarty.post.p_inv_payment_method|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoicePaymentLine1Name}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_payment_line1_name" class="si_input" tabindex="100"
                               value="{if isset($smarty.post.p_inv_payment_line1_name)}{$smarty.post.p_inv_payment_line1_name|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoicePaymentLine1Value}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_payment_line1_value" class="si_input" tabindex="110"
                               value="{if isset($smarty.post.p_inv_payment_line1_value)}{$smarty.post.p_inv_payment_line1_value|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoicePaymentLine2Name}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_payment_line2_name" class="si_input" tabindex="120"
                               value="{if isset($smarty.post.p_inv_payment_line2_name)}{$smarty.post.p_inv_payment_line2_name|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoicePaymentLine2Value}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="p_inv_payment_line2_value" class="si_input" tabindex="130"
                               value="{if isset($smarty.post.p_inv_payment_line2_value)}{$smarty.post.p_inv_payment_line2_value|htmlSafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.status}:
                        <a class="cluetip" href="#" title="{$LANG.status}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <select name="status" class="si_input" tabindex="140">
                            <option value="1" selected>{$LANG.real}</option>
                            <option value="0">{$LANG.draft}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invoiceNumberingGroup}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        {if !isset($indexInfo) }
                            <p class="si_input"><em>{$LANG.noPreferences}</em></p>
                        {else}
                            <select name="index_group" class="si_input" id="groupId" tabindex="150" onchange="checkIndexGroup()">
                                <option value="0">{$LANG.useThisPref}</option>
                                {foreach $indexInfo as $ii}
                                    <option {if $ii.pref_id == $defaults.preference} selected {/if}
                                            value="{$ii.pref_id|htmlSafe}">{$ii.pref_description|htmlSafe}
                                    </option>
                                {/foreach}
                            </select>
                            <script>
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
                            </script>
                            &nbsp;&nbsp;
                            <label for="nextIndexId" class="bold" id="nextIndexIdLabel" style="display:none">{$LANG.startingNumber}:
                                <a class="cluetip" href="#" title="{$LANG.startingNumber}" tabindex="-1"
                                   rel="index.php?module=documentation&amp;view=view&amp;page=helpStartingNumber">
                                    <img src="{$helpImagePath}help-small.png" alt=""/>
                                </a>
                            </label>
                            <input type="text" class="si_input" name="nextIndexId" id="nextIndexId" tabindex="155" style="display:none;">
                        {/if}

                    </td>
                </tr>
                <tr>
                    <th>{$LANG.setAging}:
                        <a class="cluetip" href="#" title="{$LANG.setAging}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <select name="set_aging" class="si_input" tabindex="160">
                            <option value="{$smarty.const.DISABLED}" selected>{$LANG.disabled}</option>
                            <option value="{$smarty.const.ENABLED}">{$LANG.enabled}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.language}:
                        <a class="cluetip" href="#" title="{$LANG.language}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td>
                        <select name="language" class="si_input" tabindex="170">
                            {foreach $localeList as $locale}
                                {* There is no config default for language so set same default a local. *}
                                <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.locale}:
                        <a class="cluetip" href="#" title="{$LANG.locale}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td>
                        <select name="locale" class="si_input" tabindex="175">
                            {foreach $localeList as $locale}
                                <option value="{$locale|htmlSafe}" {if $locale == $config.localLocale}selected{/if}>{$locale|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}:
                        <a class="cluetip" href="#" title="{$LANG.enabled}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <select name="pref_enabled" class="si_input" tabindex="180">
                            <option value="{$smarty.const.DISABLED}">{$LANG.disabled}</option>
                            <option value="{$smarty.const.ENABLED}" selected>{$LANG.enabled}</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}" tabindex="190">
                    <img class="button_img" src="images/tick.png" alt=""/>
                    {$LANG.save}
                </button>

                <a href="index.php?module=preferences&amp;view=manage" class="negative" tabindex="200">
                    <img src="images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>

        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
