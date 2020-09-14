<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.product_description}:</th>
                <td>{$product.description|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.product_unit_price}:</th>
                <td>{$product.unit_price|utilNumberTrim}</td>
            </tr>
            {if $defaults.inventory == $smarty.const.ENABLED}
                <tr>
                    <th class="details_screen">{$LANG.cost_uc}:</th>
                    <td>{$product.cost|utilNumber}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.reorder_level}:</th>
                    <td>{$product.reorder_level}</td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.default_tax}:</th>
                <td>{if isset($tax_selected.tax_description)}{$tax_selected.tax_description|htmlSafe}&nbsp;{/if}
                    {if isset($tax_selected.type)}{$tax_selected.type|htmlSafe}{/if}</td>
            </tr>
            {if !empty($customFieldLabel.product_cf1)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.product_cf1|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{$product.custom_field1|htmlSafe}</td>
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
                    <td>{$product.custom_field2|htmlSafe}</td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.product_cf3|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.custom_fields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields">
                            <img src="{$helpImagePath}help-small.png" alt="">
                        </a>
                    </th>
                    <td>{$product.custom_field3|htmlSafe}</td>
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
                    <td>{$product.custom_field4|htmlSafe}</td>
                </tr>
            {/if}
            {foreach $cflgs as $cflg}
                {assign var="i" value=$cflg.flg_id-1}
                <tr>
                    <th class="details_screen">{$cflg.field_label|trim|htmlSafe}:</th>
                    <td>
                        <input type="checkbox" disabled="disabled" name="custom_flags_{$cflg.flg_id}"
                               {if substr($product.custom_flags,$i,1) == $smarty.const.ENABLED}checked{/if} value="1"/>
                    </td>
                </tr>
            {/foreach}
            {if isset($defaults.product_attributes)}
                <tr>
                    <th class="details_screen">{$LANG.product_attributes}:</th>
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
                <th class="details_screen">{$LANG.notes}:</th>
                <td>{$product.notes|unescape}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.note_attributes}:</th>
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
                <th class="details_screen">{$LANG.product_enabled}:</th>
                <td>{$product.enabled_text|htmlSafe}</td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=products&amp;view=edit&amp;id={$product.id|htmlSafe}" class="positive">
            <img src="../../../images/report_edit.png" alt=""/>
            {$LANG.edit}
        </a>
    </div>
</form>
