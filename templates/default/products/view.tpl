<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=products&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.productDescription}:</th>
                <td>{$product.description|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.productUnitPrice}:</th>
                <td>{$product.unit_price|utilNumberTrim}</td>
            </tr>
            {if $defaults.inventory == $smarty.const.ENABLED}
                <tr>
                    <th class="details_screen">{$LANG.costUc}:</th>
                    <td>{$product.cost|utilNumber}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.reorderLevel}:</th>
                    <td>{$product.reorder_level}</td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.defaultTax}:</th>
                <td>{if isset($tax_selected.tax_description)}{$tax_selected.tax_description|htmlSafe}&nbsp;{/if}
                    {if isset($tax_selected.type)}{$tax_selected.type|htmlSafe}{/if}</td>
            </tr>
            {if !empty($customFieldLabel.product_cf1)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.product_cf1|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>{$product.custom_field1|htmlSafe}</td>
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
                    <td>{$product.custom_field2|htmlSafe}</td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <tr>
                    <th class="details_screen">{$customFieldLabel.product_cf3|htmlSafe}:
                        <a class="cluetip" href="#" title="{$LANG.customFields}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFields">
                            <img src="{$helpImagePath}help-small.png" alt="">
                        </a>
                    </th>
                    <td>{$product.custom_field3|htmlSafe}</td>
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
            {if isset($defaults.product_attributes) && $defaults.product_attributes == $smarty.const.ENABLED}
                <tr>
                    <th class="details_screen">{$LANG.productAttributes}:</th>
                    <td></td>
                </tr>
                {foreach $attributes as $attribute}
                    {assign "idx" $attribute.id}
                    {if $attribute.enabled == $smarty.const.ENABLED ||
                    (isset($product.attribute_decode[$idx]) && $product.attribute_decode[$idx] == 'true')}
                        <tr>
                            <td></td>
                            <th class="details_screen product_attribute">
                                <input type="checkbox" disabled="disabled" name="attribute{$idx}"
                                       {if isset($product.attribute_decode[$idx]) &&
                                       $product.attribute_decode[$idx] == 'true'}checked{/if} value="true"/>
                                {$attribute.name}
                            </th>
                        </tr>
                    {/if}
                {/foreach}
            {/if}
            {if $defaults.product_groups == $smarty.const.ENABLED}
                <tr>
                    <th class="details_screen">{$LANG.productGroupUc}:</th>
                    <td>
                        {if empty($product.product_group)}
                            &nbsp;
                        {else}
                            {$productGroup.name}{if $productGroup.markup > 0}&nbsp;({$LANG.productMarkupUc}&nbsp;=&nbsp;{$productGroup.markup}%){/if}
                        {/if}
                    </td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.notes}:</th>
                <td>{$product.notes|unescape}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.noteAttributes}:</th>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <th class="details_screen product_attribute">
                    <input type="checkbox" disabled="disabled" name="notes_as_description"
                            {if $product.notes_as_description == 'Y'} checked {/if} value='true'/>
                    {$LANG.noteAsDescription}
                </th>
            </tr>
            <tr>
                <td></td>
                <th class="details_screen product_attribute">
                    <input type="checkbox" disabled="disabled" name="show_description"
                            {if $product.show_description == 'Y'} checked {/if} value='true'/>
                    {$LANG.noteExpand}
                </th>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.productEnabled}:</th>
                <td>{$product.enabled_text|htmlSafe}</td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=products&amp;view=edit&amp;id={$product.id|htmlSafe}" class="positive">
            <img src="images/report_edit.png" alt=""/>
            {$LANG.edit}
        </a>

        <a href="index.php?module=products&amp;view=manage" class="negative">
            <img src="images/cross.png" alt=""/>{$LANG.cancel}
        </a>
    </div>
</form>
