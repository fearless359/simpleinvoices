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
                    <li><a href="#section-2" target="_top">{$LANG.custom_uc}&nbsp;{$LANG.fields_uc}&nbsp;&amp;&nbsp;{$LANG.flags_uc}</a></li>
                    <li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
                </ul>
                <div id="section-1" class="fragment">
                    <table>
                        <tr>
                            <th class="details_screen">{$LANG.description_uc}:
                                <a class="cluetip" href="#" title="{$LANG.required_field}" tabindex="-1"
                                   rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field">
                                    <img src="{$helpImagePath}required-small.png" alt=""/>
                                </a>
                            </th>
                            <td>
                                <input type="text" name="description" id="description" class="si_input validate[required]" size="50" tabindex="10"
                                       value="{if isset($smarty.post.description)}{$smarty.post.description|htmlSafe}{/if}"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.unit_price}: </th>
                            <td>
                                <input type="text" class="si_input edit" name="unit_price" size="25" tabindex="20"
                                       value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price|htmlSafe}{/if}"/>
                            </td>
                        </tr>
                        {if $defaults.inventory == '1'}
                            <tr>
                                <th class="details_screen">{$LANG.cost_uc}:
                                    <a class="cluetip" href="#" title="{$LANG.cost_uc}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_cost">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" class="si_input edit" name="cost" size="25" tabindex="30"
                                           value="{if isset($smarty.post.cost)}{$smarty.post.cost|htmlSafe}{/if}"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="details_screen">{$LANG.reorder_level}: </th>
                                <td>
                                    <input type="text" class="si_input edit" name="reorder_level" size="25" tabindex="40"
                                           value="{if isset($smarty.post.reorder_level)}{$smarty.post.reorder_level|htmlSafe}{/if}"/>
                                </td>
                            </tr>
                        {/if}
                        <tr>
                            <th class="details_screen">{$LANG.default_tax}: </th>
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
                    </table>
                </div>
                <div id="section-2" class="fragment">
                    <table>
                        {if !empty($customFieldLabel.product_cf1)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf1|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <select name="custom_field1" class="si_input" tabindex="70">
                                        <option value=""></option>
                                        {foreach $product_group as $pg}
                                            <option value="{if isset($pg.name)}{$pg.name|htmlSafe}{/if}">{$pg.name|htmlSafe}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf2)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf2|htmlSafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
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
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
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
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
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
                                <th class="details_screen">{$LANG.product_attributes}: </th>
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
                            <th class="details_screen">{$LANG.note_attributes}: </th>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="notes_as_description" class="si_input" value="true" tabindex="140"/>
                                {$LANG.note_as_description}
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="show_description" class="si_input" value="true" tabindex="150"/>
                                {$LANG.note_expand}
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}" tabindex="160">
                <img class="button_img" src="../../../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=products&amp;view=manage" class="negative" tabindex="170">
                <img src="../../../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
