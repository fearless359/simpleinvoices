<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<!--suppress HtmlFormInputWithoutLabel -->
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.descriptionUc}:
                        <a class="cluetip" href="#" title="{$LANG.descriptionUc}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefDescription">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_description}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.currencySign}:
                        <a class="cluetip" href="#" title="{$LANG.currencySign}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefCurrencySign">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_currency_sign}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.currencyCode}:
                        <a class="cluetip" href="#" title="{$LANG.currencyCode}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCurrencyCode">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.currency_code|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoiceHeading}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceHeading}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceHeading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_heading|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoiceWording}:
                        <a class="cluetip" title="{$LANG.invoiceWording}"
                           href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceWording">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_wording|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoiceDetailHeading}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceDetailHeading}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailHeading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_detail_heading|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.includeOnlinePayment}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceDetailLine">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">
                        <input type=checkbox name=include_online_payment[] {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='paypal' DISABLED>{$LANG.paypal}
                        <input type=checkbox name=include_online_payment[] {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='eway_merchant_xml' DISABLED>{$LANG.ewayMerchantXml}
                        <input type=checkbox name=include_online_payment[] {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='paymentsgateway' DISABLED>{$LANG.paymentsGateway}
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoicePaymentMethod}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentMethod}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoicePaymentMethod">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_method|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoicePaymentLine1Name}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Name}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line1_name|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoicePaymentLine1Value}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine1Value}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine1_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line1_value|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoicePaymentLine2Name}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Name}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line2_name|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoicePaymentLine2Value}:
                        <a class="cluetip" href="#" title="{$LANG.invoicePaymentLine2Value}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefPaymentLine2_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line2_value|htmlSafe}</td>
                </tr>

                <tr>
                    <th class="details_screen">{$LANG.enabled}:
                        <a class="cluetip" href="#" title="{$LANG.enabled}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceEnabled">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.enabled_text}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.status}:
                        <a class="cluetip" href="#" title="{$LANG.status}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefStatus" >
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.status_wording}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoiceNumberingGroup}:
                        <a class="cluetip" href="#" title="{$LANG.invoiceNumberingGroup}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefInvoiceNumberingGroup">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">
                        {$indexGroup.pref_description}&nbsp;&nbsp;
                        (<span class="bold;color:#777">{$LANG.nextNumber}: </span>
                        {$nextId})
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.setAging}:
                        <a class="cluetip" href="#" title="{$LANG.setAging}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpSetAging">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.set_aging_text}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.language}:
                        <a class="cluetip" href="#" title="{$LANG.language}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLanguage">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.language}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.locale}:
                        <a class="cluetip" href="#" title="{$LANG.locale}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefLocale">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.locale}</td>
                </tr>

            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=preferences&amp;view=edit&amp;id={$preference.pref_id}" class="positive">
                <img src="images/report_edit.png" alt=""/>{$LANG.edit}</a>

            <a href="index.php?module=preferences&amp;view=manage" class="negative">
                <img src="images/cross.png" alt=""/>{$LANG.cancel}</a>
        </div>
        <div class="si_help_div">
            <a class="cluetip" href="#" title="{$LANG.whatsAllThisInvPref}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefWhatThe">
                <img src="{$helpImagePath}help-small.png" alt=""/>
                {$LANG.whatsAllThisInvPref}
            </a>
        </div>
