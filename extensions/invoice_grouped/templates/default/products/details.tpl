<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id}">
    {if $smarty.get.action== 'view' }
        <table class="center">
            <tr>
                <th>{$LANG.product_description}</th>
                <td>{$product.description}</td>
            </tr>
            <tr>
                <th>{$LANG.product_unit_price}</th>
                <td>{$product.unit_price|siLocal_number}</td>
            </tr>
            <tr>
                <th>{$LANG.default_tax}</th>
                <td>
                    {$tax_selected.tax_description} {$tax_selected.type}
                </td>
            </tr>
            {if !empty($customFieldLabel.product_cf1)}
                <tr>
                    <th>{$customFieldLabel.product_cf1}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{$product.custom_field1}</td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf2)}
                <tr>
                    <th>{$customFieldLabel.product_cf2}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{$product.custom_field2}</td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <tr>
                    <th>{$customFieldLabel.product_cf3}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt="">
                        </a>
                    </th>
                    <td>{$product.custom_field3}</td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf4)}
                <tr>
                    <th>{$customFieldLabel.product_cf4}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{$product.custom_field4}</td>
                </tr>
            {/if}
            <tr>
                <th>{$LANG.notes}</th>
                <td>{$product.notes|unescape}</td>
            </tr>
            <tr>
                <th>{$LANG.product_enabled}</th>
                <td>{$product.enabled_text}</td>
            </tr>
        </table>
        <br/>
        <table class="center">
            <tr>
                <th>
                    <a href="index.php?module=products&amp;view=details&amp;id={$product.id}&amp;action=edit" class="positive">
                        <img src="../../../../../images/add.png" alt=""/>
                        {$LANG.edit}
                    </a>
                </th>
            </tr>
        </table>
    {elseif $smarty.get.action== 'edit' }
        <h3>{$LANG.product_edit}</h3>
        <table class="center">
            <tr>
                <th>{$LANG.product_description}</th>
                <td><input type="text" name="description" size="50" value="{if isset($product.description)}{$product.description}{/if}" id="description" class="required" onblur="checkField(this);"/></td>
            </tr>
            <tr>
                <th>{$LANG.product_unit_price}</th>
                <td><input type="text" name="unit_price" size="25" value="{$product.unit_price|siLocal_number}"/></td>
            </tr>
            <tr>
                <th>{$LANG.default_tax}</th>
                <td>
                    <select name="default_tax_id">
                        {foreach from=$taxes item=tax}
                            <option value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}" {if $tax.tax_id == $product.default_tax_id}selected{/if}>
                                {$tax.tax_description}
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {if !empty($customFieldLabel.product_cf1)}
                <tr>
                    <th>{$customFieldLabel.product_cf1}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                           title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <select name="custom_field1">
                            <option value=""></option>
                            {foreach from=$product_group item=pg}
                                <option value="{if isset($pg.name)}{$pg.name}{/if}" {if $pg.name == $product.custom_field1}selected{/if}>{$pg.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf2)}
                <tr>
                    <th>{$customFieldLabel.product_cf2}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                           title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="custom_field2" size="50" value="{if isset($product.custom_field2)}{$product.custom_field2}{/if}"/></td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <tr>
                    <th>{$customFieldLabel.product_cf3}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                           title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="custom_field3" size="50" value="{if isset($product.custom_field3)}{$product.custom_field3}{/if}"/></td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf4)}
                <tr>
                    <th>{$customFieldLabel.product_cf4}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
                           title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="custom_field4" size="50" value="{if isset($product.custom_field4)}{$product.custom_field4}{/if}"/></td>
                </tr>
            {/if}
            <tr>
                <th>{$LANG.notes}</th>
                <td>
                    <!--
                    <textarea name="notes" class="editor">{*$product.notes|unescape*}</textarea>
                    -->
                    <input name="notes" id="notes" {if isset($product.notes)}value="{$product.notes|outhtml}"{/if} type="hidden">
                    <trix-editor input="notes"></trix-editor>
                </td>
            </tr>
            <tr>
                <th>{$LANG.product_enabled}</th>
                <td>{html_options name=enabled options=$enabled selected=$product.enabled}</td>
            </tr>
        </table>
        <br/>
        <table class="center">
            <tr>
                <td>
                    <button type="submit" class="positive" name="save_product" value="{$LANG.save}">
                        <img class="button_img" src="../../../../../images/tick.png" alt=""/>
                        {$LANG.save}
                    </button>

                    <input type="hidden" name="op" value="edit_product">
                    <a href="index.php?module=products&amp;view=manage" class="negative">
                        <img src="../../../../../images/cross.png" alt=""/>
                        {$LANG.cancel}
                    </a>
                </td>
            </tr>
        </table>
    {/if}
</form>
