{* if product is updated or saved.*}
{if !empty($smarty.post.description)}
    {include file="templates/default/products/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=products&amp;view=create">
        <div class="si_form">
            <div id="tabs_customer">
                <ul class="anchors">
                    <li><a href="#section-1" target="_top">{$LANG.detailsUc}</a></li>
                    <li><a href="#section-2" target="_top">{$LANG.customUc}&nbsp;{$LANG.fieldsUc}&nbsp;&amp;&nbsp;{$LANG.flagsUc}</a></li>
                    <li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
                </ul>
                <div id="section-1" class="fragment">
                    <table>
                        <tr>
                            <th class="details_screen">{$LANG.descriptionUc}:
                                <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                                   rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                                    <img src="{$helpImagePath}required-small.png" alt=""/>
                                </a>
                            </th>
                            <td>
                                <input type="text" name="description" id="description" class="si_input validate[required]" size="50" tabindex="10"
                                       value="{if isset($smarty.post.description)}{$smarty.post.description|htmlSafe}{/if}"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.unitPrice}: </th>
                            <td>
                                <input type="text" class="si_input edit" name="unit_price" size="25" tabindex="20"
                                       value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price|htmlSafe}{/if}"/>
                            </td>
                        </tr>
                        {if $defaults.inventory == $smarty.const.ENABLED}
                            <tr>
                                <th class="details_screen">{$LANG.costUc}:
                                    <a class="cluetip" href="#" title="{$LANG.costUc}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCost">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" class="si_input edit" name="cost" size="25" tabindex="30"
                                           value="{if isset($smarty.post.cost)}{$smarty.post.cost|htmlSafe}{/if}"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="details_screen">{$LANG.reorderLevel}: </th>
                                <td>
                                    <input type="text" class="si_input edit" name="reorder_level" size="25" tabindex="40"
                                           value="{if isset($smarty.post.reorder_level)}{$smarty.post.reorder_level|htmlSafe}{/if}"/>
                                </td>
                            </tr>
                        {/if}
                        <tr>
                            <th class="details_screen">{$LANG.defaultTax}: </th>
                            <td>
                                <select name="default_tax_id" class="si_input" tabindex="50">
                                    <option value=''></option>
                                    {foreach $taxes as $tax}
                                        <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.enabled}: </th>
                            <td>{html_options class="edit, si_input" name=enabled options=$enabled selected=1 tabindex=60}</td>
                        </tr>
                        {if $defaults.product_groups == $smarty.const.ENABLED}
                            <tr>
                                <th class="details_screen">{$LANG.productGroupUc}:</th>
                                <td>
                                    <select name="product_group" class="si_input">
                                        <option value=''></option>
                                        {foreach $productGroups as $productGroup}
                                            <option value="{$productGroup.name|htmlSafe}"
                                                    {if isset($product.product_group) && $product.product_group == $productGroup.name}selected{/if}>{$productGroup.name|htmlSafe}{if $productGroup.markup > 0}&nbsp;({$LANG.markupUc}&nbsp;=&nbsp;{$productGroup.markup}%){/if}
                                            </option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                        {/if}
                    </table>
                </div>
                <div id="section-2" class="fragment">
                    <table>
                        {if !empty($customFieldLabel.product_cf1)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf1|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="si_input edit" name="custom_field1" size="50" tabindex="70"
                                           value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlSafe}{/if}"/></td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf2)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf2|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="si_input edit" name="custom_field2" size="50" tabindex="80"
                                           value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlSafe}{/if}"/></td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf3)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf3|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="si_input edit" name="custom_field3" size="50" tabindex="90"
                                           value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/></td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf4)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf4|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="si_input edit" name="custom_field4" size="50" tabindex="100"
                                           value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/></td>
                            </tr>
                        {/if}
                        {foreach $cflgs as $cflg}
                            <tr>
                                <th class="details_screen">
                                    {$cflg.field_label|trim|htmlSafe}:
                                    {if strlen($cflg.field_help) > 0}
                                        <a class="cluetip" href="#" title="{$cflg.field_label}" tabindex="-1"
                                           rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}">
                                            <img src="{$helpImagePath}help-small.png" alt=""/>
                                        </a>
                                    {/if}
                                </th>
                                <td><input type="checkbox" name="custom_flags_{$cflg.flg_id}" class="si_input" value="1" tabindex="11{$cflg@index}"/></td>
                            </tr>
                        {/foreach}
                        {if $defaults.product_attributes}
                            <tr>
                                <th class="details_screen">{$LANG.productAttributes}: </th>
                                <td></td>
                            </tr>
                            {foreach $attributes as $attribute}
                                <tr>
                                    <td></td>
                                    <th class="details_screen product_attribute">
                                        <input type="checkbox" name="attribute{$attribute.id}" class="si_input" value="true" tabindex="12{$attribute@index}"/>
                                        {$attribute.name}
                                    </th>
                                </tr>
                            {/foreach}
                        {/if}
                    </table>
                </div>
                <div id="section-3" class="fragment">
                    <table>
                        <tr>
                            <th class="details_screen">{$LANG.notes}: </th>
                            <td>
                                <textarea name='notes' class="si_input" rows="3" cols="80"
                                          tabindex="130">{if isset($smarty.post.notes)}{$smarty.post.notes|outHtml}{/if}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.noteAttributes}: </th>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="notes_as_description" class="si_input" value="true" tabindex="140"/>
                                {$LANG.noteAsDescription}
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="show_description" class="si_input" value="true" tabindex="150"/>
                                {$LANG.noteExpand}
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}" tabindex="160">
                <img class="button_img" src="images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=products&amp;view=manage" class="negative" tabindex="170">
                <img src="images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
