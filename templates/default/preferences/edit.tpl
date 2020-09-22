<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=preferences&amp;view=save&amp;id={$smarty.get.id}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.descriptionUc}:
                    <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                    <a class="cluetip" href="#" title="{$LANG.descriptionUc}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" class="si_input validate[required]" name='pref_description' size="50" tabindex="10"
                           value="{if isset($preference.pref_description)}{$preference.pref_description|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.currencySign}:
                    <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_currency_sign' class="si_input" size="15" tabindex="20"
                           value="{if isset($preference.pref_currency_sign)}{$preference.pref_currency_sign}{/if}"/>
                    <a class="cluetip" href="#" title="{$LANG.currencySign}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                        {$LANG.currencySignNonDollar}
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.currencyCode}:
                    <a class="cluetip" href="#" title="{$LANG.currencyCode}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCurrencyCode">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='currency_code' class="si_input" size="15" tabindex="30"
                           value="{if isset($preference.currency_code)}{$preference.currency_code}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceHeading}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceHeading}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_heading' class="si_input" size="50" tabindex="40"
                           value="{if isset($preference.pref_inv_heading)}{$preference.pref_inv_heading|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceWording}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceWording}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_wording' class="si_input" size="50" tabindex="50"
                           value="{if isset($preference.pref_inv_wording)}{$preference.pref_inv_wording|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceDetailHeading}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_detail_heading' class="si_input" size="50" tabindex="60"
                           value="{if isset($preference.pref_inv_detail_heading)}{$preference.pref_inv_detail_heading|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceDetailLine}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceDetailLine}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                        <img src="{$helpImagePath}help-small.png" alt=""/></a>
                </th>
                <td>
                    <input type="text" name='pref_inv_detail_line' class="si_input" size="75" tabindex="70"
                           value="{if isset($preference.pref_inv_detail_line)}{$preference.pref_inv_detail_line|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.includeOnlinePayment}:
                    <a class="cluetip" href="#" title="{$LANG.includeOnlinePayment}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="checkbox" name="include_online_payment[]" class="si_input" tabindex="80"
                           {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if} value='paypal'>{$LANG.paypal}
                    <input type="checkbox" name="include_online_payment[]" class="si_input" tabindex="81"
                           {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}
                           value='eway_merchant_xml'>{$LANG.ewayMerchantXml}
                    <input type="checkbox" name="include_online_payment[]" class="si_input" tabindex="82"
                           {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}
                           value='paymentsgateway'>{$LANG.paymentsGateway}
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoicePaymentMethod}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentMethod}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoicePaymentMethod">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_payment_method' class="si_input" size="50" tabindex="90"
                           value="{if isset($preference.pref_inv_payment_method)}{$preference.pref_inv_payment_method|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoicePaymentLine1Name}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_payment_line1_name' class="si_input" size="50" tabindex="100"
                           value="{if isset($preference.pref_inv_payment_line1_name)}{$preference.pref_inv_payment_line1_name|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoicePaymentLine1Value}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_payment_line1_value' class="si_input" size="50" tabindex="110"
                           value="{if isset($preference.pref_inv_payment_line1_value)}{$preference.pref_inv_payment_line1_value|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoicePaymentLine2Name}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_payment_line2_name' class="si_input" size="50" tabindex="120"
                           value="{if isset($preference.pref_inv_payment_line2_name)}{$preference.pref_inv_payment_line2_name|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoicePaymentLine2Value}:
                    <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name='pref_inv_payment_line2_value' class="si_input" size="50" tabindex="130"
                           value="{if isset($preference.pref_inv_payment_line2_value)}{$preference.pref_inv_payment_line2_value|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.status}:
                    <a class="cluetip" href="#" title="{$LANG.status}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="status" class="si_input" tabindex="140">
                        {foreach $status as $s}
                            <option {if $s.id == $preference.status} selected {/if} value="{if isset($s.id)}{$s.id}{/if}">{$s.status}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceNumberingGroup}:
                    <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td class="details_screen">
                    {if !isset($preferences) }
                        <p><em>{$LANG.noPreferences}</em></p>
                    {else}
                        <select name="index_group" class="si_input" tabindex="150">
                            {foreach $preferences as $p}
                                <option {if $p.pref_id == $preference.index_group} selected {/if} value="{$p.pref_id|htmlSafe}">{$p.pref_description|htmlSafe} ({$p.pref_id|htmlSafe})</option>
                            {/foreach}
                        </select>
                    {/if}

                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.setAging}:
                    <a class="cluetip" href="#" title="{$LANG.setAging}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="set_aging" class="si_input" tabindex="160">
                        <option value="{$smarty.const.ENABLED}" {if $preference.set_aging == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                        <option value="{$smarty.const.DISABLED}" {if $preference.set_aging != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.enabled}:
                    <a class="cluetip" href="#" title="{$LANG.enabled}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="pref_enabled" class="si_input" tabindex="170">
                        <option value="{$smarty.const.ENABLED}" {if $preference.pref_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                        <option value="{$smarty.const.DISABLED}" {if $preference.pref_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.language}:
                    <a class="cluetip" href="#" title="{$LANG.language}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="language" class="si_input" tabindex="180">
                        {foreach $localeList as $locale}
                            <option value="{$locale|htmlSafe}" {if $locale == $preference.language}selected{/if}>{$locale|htmlSafe}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.locale}:
                    <a class="cluetip" href="#" title="{$LANG.locale}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="locale" class="si_input" tabindex="190">
                        {foreach $localeList as $locale}
                            <option value="{$locale|htmlSafe}" {if $locale == $preference.locale}selected{/if}>{$locale|htmlSafe}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_preference" value="{$LANG.save}" tabindex="200">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>

            <a href="index.php?module=preferences&amp;view=manage" class="negative" tabindex="210">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>

    </div>
    <div class="si_help_div">
        <a class="cluetip" href="#" title="{$LANG.whatsAllThisInvPref}" tabindex="-1"
           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefWhatThe">
            <img src="{$helpImagePath}help-small.png" alt=""/>
            {$LANG.whatsAllThisInvPref}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
