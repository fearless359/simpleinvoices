<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    {if $smarty.get.action== 'view' }
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.product_description}: </th>
                    <td>{$product.description|htmlsafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.product_unit_price}: </th>
                    <td>{$product.unit_price|siLocal_number_trim}</td>
                </tr>
                {if $defaults.inventory == $smarty.const.ENABLED}
                    <tr>
                        <th class="details_screen">{$LANG.cost_uc}: </th>
                        <td>{$product.cost|siLocal_number}</td>
                    </tr>
                    <tr>
                        <th class="details_screen">{$LANG.reorder_level}: </th>
                        <td>{$product.reorder_level}</td>
                    </tr>
                {/if}
                <tr>
                    <th class="details_screen">{$LANG.default_tax}: </th>
                    <td>{if isset($tax_selected.tax_description)}{$tax_selected.tax_description|htmlsafe}&nbsp;{/if}
                        {if isset($tax_selected.type)}{$tax_selected.type|htmlsafe}{/if}</td>
                </tr>
                {if !empty($customFieldLabel.product_cf1)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.product_cf1|htmlsafe}: </th>
                        <td>{$product.custom_field1|htmlsafe}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.product_cf2)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.product_cf2|htmlsafe}: </th>
                        <td>{$product.custom_field2|htmlsafe}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.product_cf3)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.product_cf3|htmlsafe}: </th>
                        <td>{$product.custom_field3|htmlsafe}</td>
                    </tr>
                {/if}
                {if !empty($customFieldLabel.product_cf4)}
                    <tr>
                        <th class="details_screen">{$customFieldLabel.product_cf4|htmlsafe}: </th>
                        <td>{$product.custom_field4|htmlsafe}</td>
                    </tr>
                {/if}
                {foreach $cflgs as $cflg}
                    {assign var="i" value=$cflg.flg_id-1}
                    <tr>
                        <th class="details_screen">{$cflg.field_label|trim|htmlsafe}: </th>
                        <td>
                            <input type="checkbox" disabled="disabled" name="custom_flags_{$cflg.flg_id}"
                                    {if substr($product.custom_flags,$i,1) == $smarty.const.ENABLED}checked{/if} value="1"/>
                        </td>
                    </tr>
                {/foreach}
                {if isset($defaults.product_attributes)}
                    <tr>
                        <th class="details_screen">{$LANG.product_attributes}: </th>
                        <td></td>
                    </tr>
                    {foreach $attributes as $attribute}
                        {assign var="i" value=$attribute.id}
                        {if $attribute.enabled == $smarty.const.ENABLED ||
                            (isset($product.attribute_decode[$i]) && $product.attribute_decode[$i] == 'true')}
                            <tr>
                                <td></td>
                                <th class="details_screen product_attribute">
                                    <input type="checkbox" disabled="disabled" name="attribute{$i}"
                                           {if isset($product.attribute_decode[$i]) &&
                                               $product.attribute_decode[$i] == 'true'}checked{/if} value="true"/>
                                    {$attribute.name}
                                </th>
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
                <tr>
                    <th class="details_screen">{$LANG.notes}: </th>
                    <td>{$product.notes|unescape}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.note_attributes}: </th>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <th class="details_screen product_attribute">
                        <input type="checkbox" disabled="disabled" name="notes_as_description"
                                {if $product.notes_as_description == 'Y'} checked {/if} value='true'/>
                        {$LANG.note_as_description}
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <th class="details_screen product_attribute">
                        <input type="checkbox" disabled="disabled" name="show_description"
                                {if $product.show_description == 'Y'} checked {/if} value='true'/>
                        {$LANG.note_expand}
                    </th>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.product_enabled}: </th>
                    <td>{$product.enabled_text|htmlsafe}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=products&amp;view=details&amp;id={$product.id|htmlsafe}&amp;action=edit" class="positive">
                <img src="../../../images/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <div class="si_form">
            <div id="tabs_customer">
                <ul>
                    <li><a href="#section-1" target="_top">{$LANG.details}</a></li>
                    <li><a href="#section-2" target="_top">{$LANG.custom_uc}&nbsp;{$LANG.fields_uc}&nbsp;&amp;&nbsp;{$LANG.flags_uc}</a></li>
                    <li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
                </ul>
                <div id="section-1">
                    <table class="center">
                        <tr>
                            <th class="details_screen">{$LANG.product_description}: </th>
                            <td>
                                <input type="text" name="description" id="description" class="si_input validate[required]" size="50" tabindex="10"
                                       value="{if isset($product.description)}{$product.description|htmlsafe}{/if}"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.product_unit_price}: </th>
                            <td>
                                <input type="text" name="unit_price" class="si_input" size="25" tabindex="20"
                                       value="{$product.unit_price|siLocal_number_trim}"/>
                            </td>
                        </tr>
                        {if $defaults.inventory == '1'}
                            <tr>
                                <th class="details_screen">{$LANG.cost_uc}:
                                    <a class="cluetip" href="#" title="{$LANG.cost_uc}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" >
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" class="edit si_input" name="cost" size="25" tabindex="30"
                                           value="{$product.cost|siLocal_number}"/>
                                </td>
                            </tr>
                            <tr>
                                <th class="details_screen">{$LANG.reorder_level}: </th>
                                <td>
                                    <input type="text" class="edit si_input" name="reorder_level" size="25" tabindex="40"
                                           value="{if isset($product.reorder_level)}{$product.reorder_level|htmlsafe}{/if}"/>
                                </td>
                            </tr>
                        {/if}
                        <tr>
                            <th class="details_screen">{$LANG.default_tax}: </th>
                            <td>
                                <select name="default_tax_id" class="si_input" tabindex="50">
                                    {foreach $taxes as $tax}
                                        <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}"
                                                {if $tax.tax_id==$product.default_tax_id}selected{/if}>
                                            {$tax.tax_description|htmlsafe}
                                        </option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="section-2">
                    <table>
                        {if !empty($customFieldLabel.product_cf1)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf1|htmlsafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" name="custom_field1" class="si_input" size="50" tabindex="60"
                                           value="{if isset($product.custom_field1)}{$product.custom_field1|htmlsafe}{/if}" />
                                </td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf2)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf2|htmlsafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" name="custom_field2" class="si_input" size="50" tabindex="70"
                                           value="{if isset($product.custom_field2)}{$product.custom_field2|htmlsafe}{/if}" />
                                </td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf3)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf3|htmlsafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" name="custom_field3" class="si_input" size="50" tabindex="80"
                                           value="{if isset($product.custom_field3)}{$product.custom_field3|htmlsafe}{/if}" />
                                </td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf4)}
                            <tr>
                                <th class="details_screen">{$customFieldLabel.product_cf4|htmlsafe}:
                                    <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
                                        <img src="{$helpImagePath}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" name="custom_field4" class="si_input" size="50" tabindex="90"
                                           value="{if isset($product.custom_field4)}{$product.custom_field4|htmlsafe}{/if}" />
                                </td>
                            </tr>
                        {/if}
                        {foreach $cflgs as $cflg}
                            {assign var="i" value=$cflg.flg_id-1}
                            <tr>
                                <th class="details_screen">
                                    {$cflg.field_label|trim|htmlsafe}:
                                    {if strlen($cflg.field_help) > 0}
                                        <a class="cluetip" href="#" title="{$cflg.field_label}" tabindex="-1"
                                           rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}">
                                            <img src="{$helpImagePath}help-small.png" alt=""/>
                                        </a>
                                    {/if}
                                </th>
                                <td style="float:left;margin-left:auto;width:10px;">
                                    <input type="checkbox" name="custom_flags_{$cflg.flg_id}" class="si_input" tabindex="6{$i}"
                                           {if substr($product.custom_flags,$i,1) == '1'} checked {/if} value="1"/>
                                </td>
                            </tr>
                        {/foreach}
                        {if $defaults.product_attributes}
                            <tr>
                                <th class="details_screen">{$LANG.product_attributes}: </th>
                                <td></td>
                            </tr>
                            {foreach $attributes as $attribute}
                                {assign var="i" value=$attribute.id}
                                {if $attribute.enabled == $smarty.const.ENABLED ||
                                    (isset($product.attribute_decode[$i]) && $product.attribute_decode[$i] == 'true')}
                                    <tr>
                                        <td></td>
                                        <th class="details_screen product_attribute">
                                            <input type="checkbox" name="attribute{$i}" class="si_input" tabindex="7{$i}"
                                            {if isset($product.attribute_decode[$i]) &&
                                                $product.attribute_decode[$i] == 'true'} checked{/if} value="true" />
                                            {$attribute.name}
                                        </th>
                                    </tr>
                                {/if}
                            {/foreach}
                        {/if}
                    </table>
                </div>
                <div id="section-3">
                    <table>
                        <tr>
                            <th class="details_screen">{$LANG.notes}: </th>
                            <td>
                                <textarea name="notes" class="si_input" rows="3" cols="80" tabindex="80">{if isset($product.notes)}{$product.notes|unescape}{/if}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.note_attributes}: </th>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="notes_as_description" class="si_input" tabindex="90"
                                        {if $product.notes_as_description == 'Y'} checked {/if} value='true'/>
                                {$LANG.note_as_description}
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="show_description" class="si_input" tabindex="100"
                                        {if $product.show_description == 'Y'} checked {/if} value="true"/>
                                {$LANG.note_expand}
                            </th>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.product_enabled}: </th>
                            <td>{html_options name=enabled class=si_input options=$enabled selected=$product.enabled tabindex=110}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_product" value="{$LANG.save}" tabindex="120">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=products&amp;view=manage" class="negative" tabindex="130">
                    <img src="../../../images/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit_product">
    {/if}
</form>
