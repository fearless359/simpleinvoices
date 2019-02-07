{*
 * Script: add.tpl
 *      Customers add template
 *
 * Last edited:
 *      2016-07-27
 *
 * License:
 *      GPL v3 or above
 *}
{* if customer is updated or saved.*}
{if !empty($smarty.post.name)}
    {include file="templates/default/customers/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=customers&amp;view=add">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.customer_name}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
                           title="{$LANG.required_field}">
                            <img src="{$help_image_path}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" value="{if isset($smarty.post.name)}{$smarty.post.name|htmlsafe}{/if}"
                               size="25" class="validate[required]" tabindex="10" autofocus/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.customer_department}</th>
                    <td>
                        <input type="text" name="department" id="department" value="{if isset($smarty.post.department)}{$smarty.post.department|htmlsafe}{/if}"
                               size="25" tabindex="15"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.customer_contact}
                        <a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
                           href="#" class="cluetip" title="{$LANG.customer_contact}">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="attention" value="{if isset($smarty.post.attention)}{$smarty.post.attention|htmlsafe}{/if}"
                               size="25" tabindex="20"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street}</th>
                    <td>
                        <input type="text" name="street_address" value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlsafe}{/if}"
                               size="25" tabindex="30"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.street2}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
                           title="{$LANG.street2}">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="street_address2" value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlsafe}{/if}"
                               size="25" tabindex="40"/></td>
                </tr>
                <tr>
                    <th>{$LANG.city}</th>
                    <td><input type="text" name="city" value="{if isset($smarty.post.city)}{$smarty.post.city|htmlsafe}{/if}"
                               size="25" tabindex="50"/></td>
                </tr>
                <tr>
                    <th>{$LANG.state}</th>
                    <td><input type="text" name="state" value="{if isset($smarty.post.state)}{$smarty.post.state|htmlsafe}{/if}"
                               size="25" tabindex="60"/></td>
                </tr>
                <tr>
                    <th>{$LANG.zip}</th>
                    <td><input type="text" name="zip_code" value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlsafe}{/if}"
                               size="25" tabindex="70"/></td>
                </tr>
                <tr>
                    <th>{$LANG.country}</th>
                    <td><input type="text" name="country" value="{if isset($smarty.post.country)}{$smarty.post.country|htmlsafe}{/if}"
                               size="25" tabindex="80"/></td>
                </tr>
                <tr>
                    <th>{$LANG.phone}</th>
                    <td><input type="text" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlsafe}{/if}"
                               size="25" tabindex="90"/></td>
                </tr>
                <tr>
                    <th>{$LANG.mobile_phone}</th>
                    <td><input type="text" name="mobile_phone" value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlsafe}{/if}"
                               size="25" tabindex="100"/></td>
                </tr>
                <tr>
                    <th>{$LANG.fax}</th>
                    <td><input type="text" name="fax" value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlsafe}{/if}"
                               size="25" tabindex="110"/></td>
                </tr>
                <tr>
                    <th>{$LANG.email}</th>
                    <td><input type="text" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|htmlsafe}{/if}"
                               size="25" class="validate[required,custom[email]]" tabindex="120"/></td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_holder_name}</th>
                    <td><input type="text" name="credit_card_holder_name"
                               value="{if isset($smarty.post.credit_card_holder_name)}{$smarty.post.credit_card_holder_name|htmlsafe}{/if}"
                               size="25" tabindex="130"/></td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_number}</th>
                    <td><input type="text" name="credit_card_number"
                               value="{if isset($smarty.post.credit_card_number)}{$smarty.post.credit_card_number|htmlsafe}{/if}"
                               size="25" tabindex="140"/></td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_expiry_month}</th>
                    <td><input type="text" name="credit_card_expiry_month"
                               value="{if isset($smarty.post.credit_card_expiry_month)}{$smarty.post.credit_card_expiry_month|htmlsafe}{/if}"
                               size="5" tabindex="150"/></td>
                </tr>
                <tr>
                    <th>{$LANG.credit_card_expiry_year}</th>
                    <td><input type="text" name="credit_card_expiry_year"
                               value="{if isset($smarty.post.credit_card_expiry_year)}{$smarty.post.credit_card_expiry_year|htmlsafe}{/if}"
                               size="5" tabindex="160"/></td>
                </tr>
                {if !empty($customFieldLabel.customer_cf1)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf1|htmlsafe}
                            <a class="cluetip" href="#"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field1" value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlsafe}{/if}"
                                   size="25" tabindex="160"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf2)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf2|htmlsafe}
                            <a class="cluetip" href="#"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field2" value="{if isset($smarty.post.custom_field12)}{$smarty.post.custom_field2|htmlsafe}{/if}"
                                   size="25" tabindex="170"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf3)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf3|htmlsafe}
                            <a class="cluetip" href="#"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field3" value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlsafe}{/if}"
                                   size="25" tabindex="180"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf4)}
                    <tr>
                        <th>{$customFieldLabel.customer_cf4|htmlsafe}
                            <a class="cluetip" href="#"
                               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                               title="{$LANG.custom_fields}">
                                <img src="{$help_image_path}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field4" value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlsafe}{/if}"
                                   size="25" tabindex="190"/></td>
                    </tr>
                {/if}
                <tr>
                    <th>{$LANG.notes}</th>
                    <td>
                        <!--
                        <textarea name="notes" class="editor" tabindex="200">{*if isset($smarty.post.notes)*}{*$smarty.post.notes|outhtml*}{*/if*}</textarea>
                        -->
                        <input name="notes" id="notes" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outhtml}"{/if} type="hidden">
                        <trix-editor input="notes" tabindex="200"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>{html_options name=enabled options=$enabled selected=1 tabindex=210}</td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="220">
                    <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=customers&amp;view=manage" class="negative" tabindex="230">
                    <img src="images/common/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="add"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
