{*
 * Script: add.tpl
 *    Customers add template
 *
 * Last edited:
 *    2016-07-27
 *
 * License:
 *   GPL v3 or above
*}
{* if customer is updated or saved.*}

{if !empty($smarty.post.name)}
    {include file="templates/default/customers/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=customers&amp;view=create">
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.customerName}:
                        <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="name" id="name" class="si_input validate[required]" size="25" tabindex="10" autofocus
                               value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customerDepartment}: </th>
                    <td><input type="text" name="department" id="department" class="si_input" size="25" tabindex="15"
                               value="{if isset($smarty.post.department)}{$smarty.post.department|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customerContact}:
                        <a href="#" class="cluetip" title="{$LANG.customerContact}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomerContact">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="attention" class="si_input" size="25" tabindex="20"
                               value="{if isset($smarty.post.attention)}{$smarty.post.attention|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street}: </th>
                    <td><input type="text" name="street_address" class="si_input" size="25" tabindex="30"
                               value="{if isset($smarty.post.street_address)}{$smarty.post.street_address|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.street2}:
                        <a class="cluetip" href="#" title="{$LANG.street2}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpStreet2">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="street_address2" class="si_input" size="25" tabindex="40"
                               value="{if isset($smarty.post.street_address2)}{$smarty.post.street_address2|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.city}: </th>
                    <td><input type="text" name="city" class="si_input" size="25" tabindex="50"
                               value="{if isset($smarty.post.city)}{$smarty.post.city|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.state}: </th>
                    <td><input type="text" name="state" class="si_input" size="25" tabindex="60"
                               value="{if isset($smarty.post.state)}{$smarty.post.state|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.zip}: </th>
                    <td><input type="text" name="zip_code" class="si_input" size="25" tabindex="70"
                               value="{if isset($smarty.post.zip_code)}{$smarty.post.zip_code|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.country}: </th>
                    <td><input type="text" name="country" class="si_input" size="25" tabindex="80"
                               value="{if isset($smarty.post.country)}{$smarty.post.country|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.phoneUc}: </th>
                    <td><input type="text" name="phone" class="si_input" size="25" tabindex="90"
                               value="{if isset($smarty.post.phone)}{$smarty.post.phone|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.mobilePhone}: </th>
                    <td><input type="text" name="mobile_phone" class="si_input" size="25" tabindex="100"
                               value="{if isset($smarty.post.mobile_phone)}{$smarty.post.mobile_phone|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.fax}: </th>
                    <td><input type="text" name="fax" class="si_input" size="25" tabindex="110"
                               value="{if isset($smarty.post.fax)}{$smarty.post.fax|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.email}: </th>
                    <td><input type="text" name="email" class="si_input validate[required,custom[email]]" size="25" tabindex="120"
                               value="{if isset($smarty.post.email)}{$smarty.post.email|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.creditCardHolderName}: </th>
                    <td><input type="text" name="creditCardHolderName" class="si_input" size="25" tabindex="130"
                               value="{if isset($smarty.post.creditCardHolderName)}{$smarty.post.creditCardHolderName|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.creditCardNumber}: </th>
                    <td><input type="text" name="credit_card_number" class="si_input" size="25" tabindex="140"
                               value="{if isset($smarty.post.credit_card_number)}{$smarty.post.credit_card_number|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.creditCardExpiryMonth}: </th>
                    <td><input type="text" name="credit_card_expiry_month" class="si_input" size="5" tabindex="150"
                               value="{if isset($smarty.post.credit_card_expiry_month)}{$smarty.post.credit_card_expiry_month|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.creditCardExpiryYear}: </th>
                    <td><input type="text" name="credit_card_expiry_year" class="si_input" size="5" tabindex="160"
                               value="{if isset($smarty.post.credit_card_expiry_year)}{$smarty.post.credit_card_expiry_year|htmlSafe}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.parentCustomer}: </th>
                    <td>
                    {if empty($parent_customers)}
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <select name="parent_customer_id" class="si_input" tabindex="170">
                            <option value=''></option>
                            {foreach $parent_customers as $customer}
                                <option {if isset($defaultCustomerID) && $customer.id == $defaultCustomerID}selected{/if}
                                        value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">
                                    {$customer.name|htmlSafe}
                                </option>
                            {/foreach}
                        </select>
                    {/if}
                    </td>
                </tr>
                {if !empty($customFieldLabel.customer_cf1)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.customer_cf1|htmlSafe}:
                            <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field1" class="si_input" size="25" tabindex="180"
                                   value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlSafe}{/if}"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf2)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.customer_cf2|htmlSafe}:
                            <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field2" class="si_input" size="25" tabindex="190"
                                   value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf3)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.customer_cf3|htmlSafe}:
                            <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field3" class="si_input" size="25" tabindex="200"
                                   value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/></td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.customer_cf4)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.customer_cf4|htmlSafe}:
                            <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                               rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                <img src="{$helpImagePath}help-small.png" alt=""/>
                            </a>
                        </th>
                        <td><input type="text" name="custom_field4" class="si_input" size="25" tabindex="210"
                                   value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/></td>
                    </tr>
                {/if}
                <tr>
                    <th class="details_screen">{$LANG.notes}: </th>
                    <td>
                        <input name="notes" id="notes" type="hidden"
                               {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if}>
                        <trix-editor input="notes" class="si_input" tabindex="220"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.enabled}: </th>
                    <td>{html_options name=enabled class=si_input options=$enabled selected=$customer.enabled tabindex=230}</td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="240">
                    <img class="button_img" src="../../../../../images/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=customers&amp;view=manage" class="negative" tabindex="250">
                    <img src="../../../../../images/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
