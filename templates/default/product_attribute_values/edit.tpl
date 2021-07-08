<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute_values&amp;view=save&amp;id={$smarty.get.id}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="attributeId" class="cols__4-span-1">{$LANG.attribute}:</label>
            <select name="attribute_id" id="attributeId" class="cols__5-span-3" tabindex="10">
                {foreach $product_attributes as $product_attribute}
                    <option {if $product_attribute.id == $product_attribute_values.attribute_id}selected{/if}
                            value="{$product_attribute.id}">{$product_attribute.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="valueId" class="cols__4-span-1">{$LANG.value}:</label>
            <input type="text" name="value" id="valueId" class="cols__5-span-3" size="50" tabindex="20"
                       value="{$product_attribute_values.value}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="" class="cols__4-span-1">{$LANG.status}:</label>
            {html_options name=enabled id=enabledId class=cols__5-span-1 options=$enabled selected=$product_attribute_values.enabled tabindex=30}
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="40">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=product_attribute_values&amp;view=manage" class="button negative" tabindex="50">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
