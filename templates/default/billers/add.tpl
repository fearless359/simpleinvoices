{*
 * Script: add.tpl
 *  Biller add template
 *
 * Last edited:
*    2016-01-16 by Rich Rowley to add signature field.
 *   2008-08-25
 *
 * License:
 *   GPL v3 or above
 *}

{if !empty($smarty.post.name) && isset($smarty.post.submit) }
    {include file="templates/default/billers/save.tpl"}
{else}
    <form name="frmpost" method="post" onsubmit="return frmpost_Validator(this)"
          action="index.php?module=billers&amp;view=add">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.biller_name}
                        <a class="cluetip" href="#" title="{$LANG.required_field}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" >
                            <img src="{$help_image_path}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="name" size="25" id="name"
                               value="{if isset($smarty.post.name)}{$smarty.post.name|htmlsafe}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street}</th>
                    <td><input type="text" name="street_address" size="25"
                               value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlsafe}{/if}" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street2}
                        <a class="cluetip" href="#" title="{$LANG.street2}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_street2">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="street_address2" size="25"
                               value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlsafe}{/if}" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.city}</th>
                    <td><input type="text" name="city" size="25"
                               value="{if isset($smarty.post.city)}{$smarty.post.city|htmlsafe}{/if}" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.state}</th>
                    <td><input type="text" name="state" size="25"
                               value="{if isset($smarty.post.state)}{$smarty.post.state|htmlsafe}{/if}" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.zip}</th>
                    <td><input type="text" name="zip_code"
                               value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.country}</th>
                    <td><input type="text" name="country"
                               value="{if isset($smarty.post.country)}{$smarty.post.country|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th>{$LANG.phone}</th>
                    <td><input type="text" name="phone"
                               value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.mobile_phone}</th>
                    <td><input type="text" name="mobile_phone"
                               value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.fax}</th>
                    <td><input type="text" name="fax"
                               value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.email}</th>
                    <td><input type="text" name="email"
                               value="{if isset($smarty.post.email)}{$smarty.post.email|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.signature}</th>
                    <td><textarea name="signature" class="editor"
                                  rows="3" cols="30">{if isset($smarty.post.signature)}{$smarty.post.signature|htmlsafe}{/if}</textarea></td>
                </tr>
                <tr>
                    <th>{$LANG.paypal_business_name}</th>
                    <td><input type="text" name="paypal_business_name"
                               value="{if isset($smarty.post.paypal_business_name)}{$smarty.post.paypal_business_name|htmlsafe}{/if}" size="25"/></td>
                </tr>
                <tr>
                    <th>{$LANG.paypal_notify_url}</th>
                    <td><input type="text" name="paypal_notify_url"
                               value="{if isset($smarty.post.paypal_notify_url)}{$smarty.post.paypal_notify_url|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th>{$LANG.paypal_return_url}</th>
                    <td><input type="text" name="paypal_return_url"
                               value="{if isset($smarty.post.paypal_return_url)}{$smarty.post.paypal_return_url|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th>{$LANG.eway_customer_id}</th>
                    <td><input type="text" name="eway_customer_id"
                               value="{if isset($smarty.post.eway_customer_id)}{$smarty.post.eway_customer_id|htmlsafe}{/if}" size="50"/></td>
                </tr>
                <tr>
                    <th>{$LANG.paymentsgateway_api_id}</th>
                    <td><input type="text" name="paymentsgateway_api_id"
                               value="{if isset($smarty.post.paymentsgateway_api_id)}{$smarty.post.paymentsgateway_api_id|htmlsafe}{/if}"
                               size="50"/></td>
                </tr>
                {if !empty($customFieldLabel.biller_cf1)}
                    <tr>
                        <th>{$customFieldLabel.biller_cf1|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field1"
                                   value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1}{/if}" size="25"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf2)}
                    <tr>
                        <th>{$customFieldLabel.biller_cf2}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field2"
                                   value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlsafe}{/if}" size="25"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf3)}
                    <tr>
                        <th>{$customFieldLabel.biller_cf3|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field3"
                                   value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlsafe}{/if}" size="25"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.biller_cf4)}
                    <tr>
                        <th>{$customFieldLabel.biller_cf4|htmlsafe}
                            <a class="cluetip" href="#" title="{$LANG.custom_fields}"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field4"
                                   value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlsafe}{/if}" size="25"/></td>
                    </tr>
                {/if}
                <tr>
                    <th>{$LANG.logo_file}
                        <a class="cluetip" href="#" title="{$LANG.logo_file}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text" >
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{html_options name=logo output=$files values=$files selected=$files[0] }</td>
                </tr>
                <tr>
                    <th>{$LANG.invoice_footer}</th>
                    <td>
                         <textarea class="editor" name="footer" rows="4"
                                   cols="50">{if isset($smarty.post.footer)}{$smarty.post.footer|htmlsafe}{/if}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.notes}</th>
                    <td>
                        <textarea class="editor" name="notes" rows="8"
                                  cols="50">{if isset($smarty.post.notes)}{$smarty.post.notes|htmlsafe}{/if}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>{html_options name=enabled options=$enabled selected=1}</td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
                    <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=billers&amp;view=manage" class="negative">
                    <img src="images/common/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="add"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
