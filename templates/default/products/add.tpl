{* if product is updated or saved.*}
{if isset($smarty.post.description) && !empty($smarty.post.description) }
    {include file="templates/default/products/save.tpl"}
{else}
    {* Verify the a description was entered. *}
    {if isset($smarty.post.description) && !empty($smarty.post.description)}
        <div class="validation_alert">
            <img src="images/common/important.png" alt=""/>
            {$LANG.product_description_prompt}
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=products&amp;view=add">
        <div class="si_form">
            <div id="tabs_customer">
                <ul class="anchors">
                    <li><a href="#section-1" target="_top">{$LANG.details}</a></li>
                    <li><a href="#section-2" target="_top">{$LANG.custom_upper}&nbsp;{$LANG.fields_upper}&nbsp;&amp;&nbsp;{$LANG.flags_upper}</a></li>
                    <li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
                </ul>
                <div id="section-1" class="fragment">
                    <table>
                        <tr>
                            <th>{$LANG.description}
                                <a class="cluetip" href="#"
                                   rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
                                   title="{$LANG.required_field}">
                                    <img src="{$help_image_path}required-small.png" alt=""/>
                                </a>
                            </th>
                            <td>
                                <input type="text" name="description"
                                       value="{if isset($smarty.post.description)}{$smarty.post.description|htmlsafe}{/if}" size="50"
                                       id="description" class="validate[required]"/>
                            </td>
                        </tr>
                        <tr>
                            <th>{$LANG.unit_price}</th>
                            <td>
                                <input type="text" class="edit" name="unit_price"
                                       value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price|htmlsafe}{/if}" size="25"/>
                            </td>
                        </tr>
                        {if $defaults.inventory == '1'}
                            <tr>
                                <th>{$LANG.cost}
                                    <a class="cluetip" href="#"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_cost"
                                       title="{$LANG.cost}">
                                        <img src="{$help_image_path}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td>
                                    <input type="text" class="edit" name="cost"
                                           value="{if isset($smarty.post.cost)}{$smarty.post.cost|htmlsafe}{/if}" size="25"/>
                                </td>
                            </tr>
                            <tr>
                                <th>{$LANG.reorder_level}</th>
                                <td>
                                    <input type="text" class="edit" name="reorder_level"
                                           value="{if isset($smarty.post.reorder_level)}{$smarty.post.reorder_level|htmlsafe}{/if}" size="25"/>
                                </td>
                            </tr>
                        {/if}
                        <tr>
                            <th>{$LANG.default_tax}</th>
                            <td>
                                <select name="default_tax_id">
                                    <option value=''></option>
                                    {foreach from=$taxes item=tax}
                                        <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}">{$tax.tax_description|htmlsafe}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>{$LANG.enabled}</th>
                            <td>{html_options class=edit name=enabled options=$enabled selected=1}</td>
                        </tr>
                    </table>
                </div>
                <div id="section-2" class="fragment">
                    <table>
                        {if !empty($customFieldLabel.product_cf1)}
                            <tr>
                                <th>{$customFieldLabel.product_cf1|htmlsafe}
                                    <a class="cluetip" href="#"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                                       title="{$LANG.custom_fields}">
                                        <img src="{$help_image_path}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="edit" name="custom_field1"
                                           value="{if isset($smarty.post.custom_field1)}{$smarty.post.custom_field1|htmlsafe}{/if}" size="50"/></td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf2)}
                            <tr>
                                <th>{$customFieldLabel.product_cf2|htmlsafe}
                                    <a class="cluetip" href="#"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                                       title="{$LANG.custom_fields}">
                                        <img src="{$help_image_path}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="edit" name="custom_field2"
                                           value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2|htmlsafe}{/if}" size="50"/></td>
                            </tr>
                        {/if} {if !empty($customFieldLabel.product_cf3)}
                            <tr>
                                <th>{$customFieldLabel.product_cf3|htmlsafe}
                                    <a class="cluetip" href="#"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                                       title="{$LANG.custom_fields}">
                                        <img src="{$help_image_path}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="edit" name="custom_field3"
                                           value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlsafe}{/if}" size="50"/></td>
                            </tr>
                        {/if}
                        {if !empty($customFieldLabel.product_cf4)}
                            <tr>
                                <th>{$customFieldLabel.product_cf4|htmlsafe}
                                    <a class="cluetip" href="#"
                                       rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                                       title="{$LANG.custom_fields}">
                                        <img src="{$help_image_path}help-small.png" alt=""/>
                                    </a>
                                </th>
                                <td><input type="text" class="edit" name="custom_field4"
                                           value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlsafe}{/if}" size="50"/></td>
                            </tr>
                        {/if}
                        {foreach from=$cflgs item=cflg}
                            <tr>
                                <th>
                                    {$cflg.field_label|trim|htmlsafe}
                                    {if strlen($cflg.field_help) > 0}
                                        <a class="cluetip" href="#"
                                           rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}"
                                           title="{$cflg.field_label}">
                                            <img src="{$help_image_path}help-small.png" alt=""/>
                                        </a>
                                    {/if}
                                </th>
                                <td><input type="checkbox" name="custom_flags_{$cflg.flg_id}" value="1"/></td>
                            </tr>
                        {/foreach}
                        {if $defaults.product_attributes}
                            <tr>
                                <th class="details_screen">{$LANG.product_attributes}</th>
                                <td></td>
                            </tr>
                            {foreach from=$attributes item=attribute}
                                <tr>
                                    <td></td>
                                    <th class="details_screen product_attribute">
                                        <input type="checkbox" name="attribute{$attribute.id}" value="true"/>
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
                            <th>{$LANG.notes}</th>
                            <td>
                                <textarea name='notes' rows="3" cols="80"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="details_screen">{$LANG.note_attributes}</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="notes_as_description" value='true'/>
                                {$LANG.note_as_description}
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" name="show_description" value='true'/>
                                {$LANG.note_expand}
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=products&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        </div>
        <input type="hidden" name="op" value="insert_product"/>
    </form>
{/if}
