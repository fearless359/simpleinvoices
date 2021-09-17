{*
 *  Script: create.tpl
 *      Product Attribute Values add template
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if !empty($smarty.post.value) && isset($smarty.post.submit) }
    {include file="templates/default/product_attribute_values/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=product_attribute_values&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="attributeId" class="cols__4-span-1">{$LANG.attribute}:</label>
                <select name="attribute_id" id="attributeId" class="cols__5-span-2">
                    {foreach $product_attributes as $product_attribute}
                        <option value="{$product_attribute.id}">{$product_attribute.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="nameId" class="cols__4-span-1">{$LANG.value}:</label>
                <input type="text" name="value" id="nameId" class="cols__5-span-2" required
                       {if isset($smarty.post.value)}value="{$smarty.post.value}"{/if} size="25"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__4-span-1">{$LANG.enabled}:</label>
                {html_options name=enabled id=enabledId class="cols__5-span-1" options=$enabled selected=1}
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=product_attribute_values&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
