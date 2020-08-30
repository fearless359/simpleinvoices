{*
 * Script: details.tpl
 *   Biller details template
 *
 * Last edited:
 *   2018-09-21 by Rich Rowley to add signature field.
 *   2008-08-25
 *
 * License:
 *   GPL v3 or above
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=billers&amp;view=save&amp;id={$smarty.get.id}">
    {if $smarty.get.action== 'view' }
        <input type="hidden" name="op" value="view_biller">
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.biller_name}:</th>
                    <td class="si_input">{$biller.name}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street}:</th>
                    <td class="si_input">{$biller.street_address}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street2}:</th>
                    <td class="si_input">{$biller.street_address2}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.city}:</th>
                    <td class="si_input">{$biller.city}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.zip}:</th>
                    <td class="si_input">{$biller.zip_code}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.state}:</th>
                    <td class="si_input">{$biller.state}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.country}:</th>
                    <td class="si_input">{$biller.country}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.mobile_phone}:</th>
                    <td class="si_input">{$biller.mobile_phone}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.phone}:</th>
                    <td class="si_input">{$biller.phone}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.fax}:</th>
                    <td class="si_input">{$biller.fax}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.email}:</th>
                    <td class="si_input">{$biller.email}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.signature}:</th>
                    <td class="si_input">{$biller.signature}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_business_name}:</th>
                    <td class="si_input">{$biller.paypal_business_name}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_notify_url}:</th>
                    <td class="si_input">{$biller.paypal_notify_url}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_return_url}:</th>
                    <td class="si_input">{$biller.paypal_return_url}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.eway_customer_id}:</th>
                    <td class="si_input">{$biller.eway_customer_id}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paymentsgateway_api_id}:</th>
                    <td class="si_input">{$biller.paymentsgateway_api_id}</td>
                </tr>
                {if !empty($customFieldLabel.biller_cf1)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf1|htmlsafe}:</th>
                        <td class="si_input">{$biller.custom_field1}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf2)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf2|htmlsafe}:</th>
                        <td class="si_input">{$biller.custom_field2}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf3)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf3|htmlsafe}:</th>
                        <td class="si_input">{$biller.custom_field3}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf4)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf4|htmlsafe}:</th>
                        <td class="si_input">{$biller.custom_field4}</td>
                    </tr>
                {/if}
                <tr>
                    <th class="details_screen">{$LANG.logo_file}:</th>
                    <td class="si_input">
                        {if $biller.logo != ''}
                            <img src="templates/invoices/logos/{$biller.logo}" alt="{$biller.logo}">
                            <br/>
                            {$biller.logo}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_footer}:</th>
                    <td class="si_input">{$biller.footer}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.notes}:</th>
                    <td class="si_input">{$biller.notes}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.enabled}:</th>
                    <td class="si_input">{$biller.enabled_text}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=billers&amp;view=details&amp;action=edit&amp;id={$biller.id}" class="positive">
                <img src="../../../images/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=billers&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    {/if}
    {* ######################################################################################### *}
    {if $smarty.get.action== 'edit' }
        <div class="si_form">
            <input type="hidden" name="domain_id" value="{if isset($biller.domain_id)}{$biller.domain_id}{/if}"/>
            <table class="center" style="width:80%;">
                <tr>
                    <th class="details_screen">{$LANG.biller_name}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
                           title="{$LANG.required_field}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" class="si_input validate[required]" name="name" tabindex="10"
                               value="{if isset($biller.name)}{$biller.name|htmlsafe}{/if}" size="50" id="name"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street}</th>
                    <td><input type="text" class="si_input" name="street_address" tabindex="20"
                               value="{if isset($biller.street_address)}{$biller.street_address|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street2}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
                           title="{$LANG.street2}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" class="si_input" name="street_address2" tabindex="30"
                               value="{if isset($biller.street_address2)}{$biller.street_address2|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.city}</th>
                    <td><input type="text" class="si_input" name="city" tabindex="40"
                               value="{if isset($biller.city)}{$biller.city|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.zip}</th>
                    <td><input type="text" class="si_input" name="zip_code" tabindex="50"
                               value="{if isset($biller.zip_code)}{$biller.zip_code|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.state}</th>
                    <td><input type="text" class="si_input" name="state" tabindex="60"
                               value="{if isset($biller.state)}{$biller.state|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.country}</th>
                    <td><input type="text" class="si_input" name="country" tabindex="70"
                               value="{if isset($biller.country)}{$biller.country|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.mobile_phone}</th>
                    <td><input type="text" class="si_input" name="mobile_phone" tabindex="80"
                               value="{if isset($biller.mobile_phone)}{$biller.mobile_phone|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.phone}</th>
                    <td><input type="text" class="si_input" name="phone" tabindex="90"
                               value="{if isset($biller.phone)}{$biller.phone|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.fax}</th>
                    <td><input type="text" class="si_input" name="fax" tabindex="100"
                               value="{if isset($biller.fax)}{$biller.fax|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.email}</th>
                    <td><input type="text" class="si_input" name="email" tabindex="110"
                               value="{if isset($biller.email)}{$biller.email|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.signature}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_signature"
                           title="{$LANG.signature}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input name="signature" id="signature" {if isset($biller.signature)}value="{$biller.signature|outhtml}"{/if} type="hidden">
                        <trix-editor class="si_input" input="signature" tabindex="120"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_business_name}</th>
                    <td>
                        <input type="text" class="si_input" name="paypal_business_name" tabindex="130"
                               value="{if isset($biller.paypal_business_name)}{$biller.paypal_business_name|htmlsafe}{/if}" size="25"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_notify_url}</th>
                    <td>
                        <input type="text" class="si_input" name="paypal_notify_url" tabindex="140"
                               value="{if isset($biller.paypal_notify_url)}{$biller.paypal_notify_url|htmlsafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paypal_return_url}</th>
                    <td>
                        <input type="text" class="si_input" name="paypal_return_url" tabindex="150"
                               value="{if isset($biller.paypal_return_url)}{$biller.paypal_return_url|htmlsafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.eway_customer_id}</th>
                    <td>
                        <input type="text" class="si_input" name="eway_customer_id" tabindex="160"
                               value="{if isset($biller.eway_customer_id)}{$biller.eway_customer_id|htmlsafe}{/if}" size="50"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.paymentsgateway_api_id}</th>
                    <td>
                        <input type="text" class="si_input" name="paymentsgateway_api_id" tabindex="170"
                               value="{if isset($biller.paymentsgateway_api_id)}{$biller.paymentsgateway_api_id|htmlsafe}{/if}" size="50"/>
                    </td>
                </tr>
                {if !empty($customFieldLabel.biller_cf1)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf1|htmlsafe}
                            <a class="cluetip" href="#" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" class="si_input" name="custom_field1" tabindex="180"
                                   value="{if isset($biller.custom_field1)}{$biller.custom_field1|htmlsafe}{/if}" size="50">
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf2)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf2|htmlsafe}
                            <a class="cluetip" href="#" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" class="si_input" name="custom_field2" tabindex="190"
                                   value="{if isset($biller.custom_field2)}{$biller.custom_field2}{/if}" size="50">
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf3)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf3|htmlsafe}
                            <a class="cluetip" href="#" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields|htmlsafe}">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" class="si_input" name="custom_field3" tabindex="200"
                                   value="{if isset($biller.custom_field3)}{$biller.custom_field3|htmlsafe}{/if}" size="50">
                        </td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf4)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.biller_cf4|htmlsafe}
                            <a class="cluetip" href="#" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td>
                            <input type="text" class="si_input" name="custom_field4" tabindex="210"
                                   value="{if isset($biller.custom_field4)}{$biller.custom_field4|htmlsafe}{/if}" size="50">
                        </td>
                    </tr>
                {/if}
                <tr>
                    <th class="details_screen">{$LANG.logo_file}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text"
                           title="{$LANG.logo_file}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        {html_options name=logo class=si_input output=$files values=$files selected=$biller.logo tabindex=220}
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_footer}</th>
                    <td>
                        <input name="footer" id="footer" {if isset($biller.footer)}value="{$biller.footer|outhtml}"{/if} type="hidden">
                        <trix-editor class="si_input" input="footer" tabindex="230"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.notes}</th>
                    <td>
                        <input name="notes" id="notes" {if isset($biller.notes)}value="{$biller.notes|outhtml}"{/if} type="hidden">
                        <trix-editor class="si_input" input="notes" tabindex="240"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.enabled}</th>
                    <td>
                        {html_options name=enabled class=si_input options=$enabled selected=$biller.enabled tabindex=250}
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_biller" value="{$LANG.save_biller}" tabindex="260">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=billers&amp;view=manage" class="negative" tabindex="270">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit">
        <input type="hidden" name="category" value="1"/>
    {/if}
</form>
