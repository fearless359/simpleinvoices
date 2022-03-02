{*
 *  Script: edit.tpl
 *      Product Attributes update template
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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="nameId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.nameUc}:</label>
            <input type="text" name="name" id="nameId" class="cols__5-span-2" required size="50" tabindex="10"
                   value="{if isset($product_attribute.name)}{$product_attribute.name}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="typeId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.type}:</label>
            <select name="type_id" id="typeId" class="cols__5-span-1" tabindex="20">
                {foreach $types as $k => $v}
                    <option value="{if isset($v.id)}{$v.id}{/if}" {if $product_attribute.type_id == $v.id} selected {/if}>{$LANG[$v.name]}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.enabled}:</label>
            {html_options name=enabled id=enabledId class=cols__5-span-1 options=$enabled selected=$product_attribute.enabled tabindex=30}
        </div>
        <div class="grid__container grid__head-10">
            <label for="visibleId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.visible}:</label>
            {html_options name=visible id=visibleId class=cols__5-span-1 options=$enabled selected=$product_attribute.visible tabindex=40}
        </div>
    </div>
    <div class="align__text-center margin__top-2">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=product_attribute&amp;view=manage" class="button negative" tabindex="60">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
