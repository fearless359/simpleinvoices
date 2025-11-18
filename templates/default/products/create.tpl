{*
 *  Script: create.tpl
 *      Products add template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if !empty($smarty.post.description)}
    {include file="templates/default/products/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=products&amp;view=create">
        <div class="flex__area">
            <div class="flex__container flex__start">
                <label for="description" class="margin__right-1">{$LANG.descriptionUc}:
                    <img class="tooltip" title="{$LANG.requiredField}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="description" id="description" required size="50" tabindex="10"
                       value="{if isset($smarty.post.description)}{$smarty.post.description|htmlSafe}{/if}"/>
            </div>
            <div class="flex__container flex__start">
                <label for="unitPriceId" class="margin__right-1">{$LANG.unitPrice}:</label>
                <input type="text" name="unit_price" id="unitPriceId" size="25" tabindex="20"
                       value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price|htmlSafe}{/if}"/>
            </div>
            {if $defaults.inventory == $smarty.const.ENABLED}
                <div class="flex__container flex__start">
                    <label for="costId" class="margin__right-1">{$LANG.costUc}:
                        <img class="tooltip" title="{$LANG.helpCost}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <input type="text" name="cost" id="costId" size="25" tabindex="30"
                           value="{if isset($smarty.post.cost)}{$smarty.post.cost|htmlSafe}{/if}"/>
                </div>
                <div class="flex__container flex__start">
                    <label for="reorderLevelId"
                           class="margin__right-1">{$LANG.reorderLevel}:</label>
                    <input type="text" name="reorder_level" id="reorderLevelId" size="25" tabindex="40"
                           value="{if isset($smarty.post.reorder_level)}{$smarty.post.reorder_level|htmlSafe}{/if}"/>
                </div>
            {/if}
            <div class="flex__container flex__start">
                <label for="defaultTaxId" class="margin__right-1">{$LANG.defaultTax}:</label>
                <select name="default_tax_id" id="defaultTaxId" tabindex="50">
                    <option value=''></option>
                    {foreach $taxes as $tax}
                        <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            {if $defaults.product_groups == $smarty.const.ENABLED}
                <div class="flex__container flex__start">
                    <label for="productGroupId" class="margin__right-1">{$LANG.productGroupUc}:</label>
                    <select name="product_group" id="productGroupId">
                        <option value=''></option>
                        {foreach $productGroups as $productGroup}
                            <option value="{$productGroup.name|htmlSafe}"
                                    {if isset($product.product_group) &&
                                    $product.product_group == $productGroup.name}selected{/if}>{$productGroup.name|htmlSafe}{if $productGroup.markup > 0}&nbsp;({$LANG.markupUc}&nbsp;=&nbsp;{$productGroup.markup}&percnt;){/if}
                            </option>
                        {/foreach}
                    </select>
                </div>
            {/if}
            <div class="flex__container flex__start">
                <label for="enabledId" class="margin__right-1">{$LANG.enabled}:</label>
                {html_options name=enabled id=enabledId options=$enabled selected=1 tabindex=60}
            </div>
        </div>
        <div class="delay__display" id="tabs_customer">
            <div class="grid__area">
                <ul class="anchors">
                    <li><a href="#section-1" target="_top">{$LANG.customUc}&nbsp;{$LANG.fieldsUc}
                            &nbsp;&amp;&nbsp;{$LANG.flagsUc}</a></li>
                    <li><a href="#section-2" target="_top">{$LANG.notes}</a></li>
                </ul>
                <div id="section-1" class="fragment width_100">
                    {if !empty($customFieldLabel.product_cf1)}
                        <div class="flex__container flex__start">
                            <label for="customField1" class="margin__right-1">{$customFieldLabel.product_cf1|htmlSafe}:
                                <img class="tooltip" title="{$LANG.helpCustomFields}"
                                     src="{$helpImagePath}help-small.png" alt=""/>
                            </label>
                            <input type="text" name="custom_field1" id="customField1" size="50" tabindex="70"
                                   {if isset($smarty.post.custom_field1)}value="{$smarty.post.custom_field1|htmlSafe}"{/if}/>
                        </div>
                    {/if}
                    {if !empty($customFieldLabel.product_cf2)}
                        <div class="flex__container flex__start">
                            <label for="customField2" class="margin__right-1">{$customFieldLabel.product_cf2|htmlSafe}:
                                <img class="tooltip" title="{$LANG.helpCustomFields}"
                                     src="{$helpImagePath}help-small.png" alt=""/>
                            </label>
                            <input type="text" name="custom_field2" id="customField2" size="50" tabindex="70"
                                   {if isset($smarty.post.custom_field2)}value="{$smarty.post.custom_field2|htmlSafe}"{/if}/>
                        </div>
                    {/if}
                    {if !empty($customFieldLabel.product_cf3)}
                        <div class="flex__container flex__start">
                            <label for="customField3" class="margin__right-1">{$customFieldLabel.product_cf3|htmlSafe}:
                                <img class="tooltip" title="{$LANG.helpCustomFields}"
                                     src="{$helpImagePath}help-small.png" alt="help"/>
                            </label>
                            <input type="text" name="custom_field3" id="customField3" size="50" tabindex="90"
                                   value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3|htmlSafe}{/if}"/>
                        </div>
                    {/if}
                    {if !empty($customFieldLabel.product_cf4)}
                        <div class="flex__container flex__start">
                            <label for="customField4" class="margin__right-1">{$customFieldLabel.product_cf4|htmlSafe}:
                                <img class="tooltip" title="{$LANG.helpCustomFields}"
                                     src="{$helpImagePath}help-small.png" alt="help"/>
                            </label>
                            <input type="text" name="custom_field4" id="customField4" size="50" tabindex="100"
                                   value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4|htmlSafe}{/if}"/>
                        </div>
                    {/if}
                    {if !empty($cflgs)}
                        <div class="flex__container flex__start">
                            <div class="bold underline">{$LANG.customFlagsUc}</div>
                        </div>
                        {foreach $cflgs as $cflg}
                            <div class="grid__container grid__head-checkbox">
                                <input type="checkbox" name="custom_flags_{$cflg.flg_id}" id="custom_flags_{$cflg.flg_id}"
                                       class="cols__1-span-1 margin__top-0-5" value="1" tabindex="11{$cflg@index}"/>
                                <label for="custom_flags_{$cflg.flg_id}"
                                       class="cols__2-span-1 margin__top-0">{$cflg.field_label|utilTrim|htmlSafe}
                                    {if strlen($cflg.field_help) > 0}
                                        <img class="tooltip" title="{$cflg.field_help}"
                                             src="{$helpImagePath}help-small.png" alt="help"/>
                                    {/if}
                                </label>
                            </div>
                        {/foreach}
                    {/if}
                    {if $defaults.product_attributes}
                        <div class="flex__container flex__start">
                            <div class="bold underline">{$LANG.productAttributes}</div>
                        </div>
                        {foreach $attributes as $attribute}
                            <div class="grid__container grid__head-checkbox">
                                <input type="checkbox" name="attribute{$attribute.id}" id="attribute{$attribute.id}"
                                       class="cols__1-span-1 margin__top-0-5" value="true" tabindex="12{$attribute@index}"/>
                                <label for="attribute{$attribute.id}"
                                       class="cols__2-span-1 margin__top-0">{$attribute.name}</label>
                            </div>
                        {/foreach}
                    {/if}
                </div>

                <div id="section-2" class="fragment width_100">
                    <div class="flex__container flex__start">
                        <label for="notesId" class="underline">{$LANG.notes}:</label>
                    </div>
                    <div class="flex__container flex__start">
                        <textarea name='notes' id="notesId" rows="3" cols="80"
                                  tabindex="130">{if isset($smarty.post.notes)}{$smarty.post.notes|outHtml}{/if}</textarea>
                    </div>
                    <div class="flex__container flex__start">
                        <div class="bold underline">{$LANG.noteAttributes}</div>
                    </div>
                    <div class="grid__container grid__head-checkbox">
                        <input type="checkbox" name="notes_as_description" id="notesAsDescId" class="cols__1-span-1"
                               value="true" tabindex="140"/>
                        <label for="notesAsDescId" class="cols__2-span-1 margin__top-0">{$LANG.noteAsDescription}</label>
                    </div>
                    <div class="grid__container grid__head-checkbox">
                        <input type="checkbox" name="show_description" id="showDescId" class="cols__1-span-1"
                               value="true" tabindex="150"/>
                        <label for="showDescId" class="cols__2-span-1 margin__top-0">{$LANG.noteExpand}</label>
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
        <div class="align__text-center">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}" tabindex="160">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=products&amp;view=manage" class="button negative" tabindex="170"> <img
                        src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
