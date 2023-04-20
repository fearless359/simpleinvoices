{*
 *  Script: view.tpl
 *      Product details template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.productDescription}:</div>
        <div class="cols__4-span-5">{$product.description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.productUnitPrice}:</div>
        <div class="cols__4-span-5">{$product.unit_price|utilNumber}</div>
    </div>
    {if $defaults.inventory == $smarty.const.ENABLED}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.costUc}:</div>
            <div class="cols__4-span-5">{$product.cost|utilNumber}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.reorderLevel}:</div>
            <div class="cols__4-span-5">{$product.reorder_level}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.defaultTax}:</div>
        <div class="cols__4-span-5">{if isset($tax_selected.tax_description)}{$tax_selected.tax_description|htmlSafe}&nbsp;{/if}
            {if isset($tax_selected.type)}{$tax_selected.type|htmlSafe}{/if}</div>
    </div>
    {if $defaults.product_groups == $smarty.const.ENABLED}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.productGroupUc}:</div>
            <div class="cols__4-span-5">
                {if !empty($product.product_group)}
                    {$productGroup.name}{if $productGroup.markup > 0}&nbsp;({$LANG.productMarkupUc}&nbsp;=&nbsp;{$productGroup.markup}%){/if}
                {/if}
            </div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.productEnabled}:</div>
        <div class="cols__4-span-2">{$product.enabled_text|htmlSafe}</div>
    </div>
</div>
<div class="delay__display" id="tabs_customer">
    <div class="grid__area"></div>
    <ul class="anchors">
        <li><a href="#section-1" target="_top">{$LANG.customUc}&nbsp;{$LANG.fieldsUc}&nbsp;&amp;&nbsp;{$LANG.flagsUc}</a></li>
        <li><a href="#section-2" target="_top">{$LANG.notes}</a></li>
    </ul>
    <div id="section-1" class="fragment width_100">
        <div class="grid__area">
            {if !empty($customFieldLabel.product_cf1)}
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.product_cf1|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </div>
                    <div class="cols__4-span-5">{$product.custom_field1|htmlSafe}</div>
                </div>
            {/if}
            {if !empty($customFieldLabel.product_cf2)}
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.product_cf2|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </div>
                    <div class="cols__4-span-5">{$product.custom_field2|htmlSafe}</div>
                </div>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.product_cf3|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt="">
                    </div>
                    <div class="cols__4-span-5">{$product.custom_field3|htmlSafe}</div>
                </div>
            {/if}
            {if !empty($customFieldLabel.product_cf4)}
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$customFieldLabel.product_cf4|htmlSafe}:
                        <img class="tooltip" title="{$LANG.helpCustomFields}" src="{$helpImagePath}help-small.png" alt=""/>
                    </div>
                    <div class="cols__4-span-5">{$product.custom_field4|htmlSafe}</div>
                </div>
            {/if}
            {if !empty($cflgs)}
                <div class="grid__container grid__head-10">
                    <div class="cols__2-span-9 bold underline">{$LANG.customFlagsUc}</div>
                </div>
                {foreach $cflgs as $cflg}
                    {assign var="i" value=$cflg.flg_id-1}
                    <div class="grid__container grid__head-10">
                        <div class="cols__2-span-8">
                            <div class="grid__container grid__head-checkbox">
                                <input type="checkbox" name="custom_flags_{$cflg.flg_id}" id="custom_flags_{$cflg.flg_id}Id" disabled
                                       class="cols__1-span-1 margin__top-0-5"
                                       {if substr($product.custom_flags,$i,1) == $smarty.const.ENABLED}checked{/if} value="1"/>
                                <label for="custom_flags_{$cflg.flg_id}Id" class="cols__2-span-1 margin__top-0">{$cflg.field_label|trim|htmlSafe}:</label>
                            </div>
                        </div>
                    </div>
                {/foreach}
            {/if}
            {if isset($defaults.product_attributes) && $defaults.product_attributes == $smarty.const.ENABLED}
                <div class="grid__container grid__head-10">
                    <div class="cols__2-span-2 bold underline">{$LANG.productAttributes}</div>
                </div>
                {foreach $attributes as $attribute}
                    {assign "idx" $attribute.id}
                    {if $attribute.enabled == $smarty.const.ENABLED ||
                    (isset($product.attribute_decode[$idx]) && $product.attribute_decode[$idx] == 'true')}
                        <div class="grid__container grid__head-10">
                            <div class="cols__2-span-8">
                                <div class="grid__container grid__head-checkbox">
                                    <input type="checkbox" name="attribute{$idx}" id="attribute{$idx}Id" disabled
                                           class="cols__1-span-1 margin__top-0-5"
                                           {if isset($product.attribute_decode[$idx]) &&
                                           $product.attribute_decode[$idx] == 'true'}checked{/if} value="true"/>
                                    <label for="attribute{$idx}Id" class="cols__2-span-1 margin__top-0">{$attribute.name}</label>
                                </div>
                            </div>
                        </div>
                    {/if}
                {/foreach}
            {/if}
        </div>
    </div>

    <div id="section-2" class="fragment width_100">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-3 bold underline">{$LANG.notes}:</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-8">{$product.notes|unescape}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-3 bold underline">{$LANG.noteAttributes}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-9">
                    <div class="grid__container grid__head-checkbox">
                        <input type="checkbox" name="notes_as_description" id="notesAsDescId" class="cols__1-span-1 margin__top-0-5" disabled
                                {if $product.notes_as_description == 'Y'} checked {/if} value='true'/>
                        <label for="notesAsDescId" class="cols__2-span-1 margin__top-0">{$LANG.noteAsDescription}</label>
                    </div>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-9">
                    <div class="grid__container grid__head-checkbox">
                        <input type="checkbox" name="show_description" id="showDescId" class="cols__1-span-1 margin__top-0-5" disabled
                               {if $product.show_description == 'Y'}checked{/if} value='true'/>
                        <label for="showDescId" class="cols__2-span-1 margin__top-0">{$LANG.noteExpand}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    {* This causes the tabs to appear after being rendered *}
    {literal}
    $(document).ready(function () {
        $("div.delay__display").removeClass("delay__display");
    });
    {/literal}
</script>

<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=products&amp;view=edit&amp;id={$product.id|htmlSafe}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=products&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt=""/>{$LANG.cancel}
    </a>
</div>
