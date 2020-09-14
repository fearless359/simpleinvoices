<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<!--suppress HtmlFormInputWithoutLabel -->
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.description_uc}:
                        <a class="cluetip" href="#" title="{$LANG.description_uc}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_description}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.currency_sign}:
                        <a class="cluetip" href="#" title="{$LANG.currency_sign}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_currency_sign}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.currency_code}:
                        <a class="cluetip" href="#" title="{$LANG.currency_code}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.currency_code|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_heading}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_heading}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_heading|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_wording}:
                        <a class="cluetip" title="{$LANG.invoice_wording}"
                           href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_wording|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_detail_heading}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_detail_heading}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_detail_heading|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.include_online_payment}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">
                        <input type=checkbox name=include_online_payment[] {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='paypal' DISABLED>{$LANG.paypal}
                        <input type=checkbox name=include_online_payment[] {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='eway_merchant_xml' DISABLED>{$LANG.eway_merchant_xml}
                        <input type=checkbox name=include_online_payment[] {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if}
                               value='paymentsgateway' DISABLED>{$LANG.paymentsgateway}
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_payment_method}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_payment_method}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_method|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_payment_line_1_name}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_payment_line_1_name}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line1_name|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_payment_line_1_value}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_payment_line_1_value}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line1_value|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_payment_line_2_name}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_payment_line_2_name}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line2_name|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_payment_line_2_value}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_payment_line_2_value}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.pref_inv_payment_line2_value|htmlSafe}</td>
                </tr>

                <tr>
                    <th class="details_screen">{$LANG.enabled}:
                        <a class="cluetip" href="#" title="{$LANG.enabled}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.enabled_text}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.status}:
                        <a class="cluetip" href="#" title="{$LANG.status}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" >
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.status_wording}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_numbering_group}:
                        <a class="cluetip" href="#" title="{$LANG.invoice_numbering_group}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$index_group.pref_description} ({$index_group.pref_id})</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.set_aging}:
                        <a class="cluetip" href="#" title="{$LANG.set_aging}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_set_aging">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.set_aging_text}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.language}:
                        <a class="cluetip" href="#" title="{$LANG.language}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.language}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.locale}:
                        <a class="cluetip" href="#" title="{$LANG.locale}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$preference.locale}</td>
                </tr>

            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=preferences&amp;view=edit&amp;id={$preference.pref_id}" class="positive">
                <img src="../../../images/report_edit.png" alt=""/>{$LANG.edit}</a>

            <a href="index.php?module=preferences&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>{$LANG.cancel}</a>
        </div>
        <div class="si_help_div">
            <a class="cluetip" href="#" title="{$LANG.whats_all_this_inv_pref}"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the">
                <img src="{$helpImagePath}help-small.png" alt=""/>
                {$LANG.whats_all_this_inv_pref}
            </a>
        </div>
