{*
 *  Script: edit.tpl
 *      Product Attribute Values edit template
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
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute_values&amp;view=save&amp;id={$smarty.get.id}">
    <div class="flex__area">
        <div class="flex__container flex__start">
            <label for="attributeId" class="margin__right-1">{$LANG.attribute}:</label>
            <select name="attribute_id" id="attributeId" tabindex="10">
                {foreach $product_attributes as $product_attribute}
                    <option {if $product_attribute.id == $product_attribute_values.attribute_id}selected{/if}
                            value="{$product_attribute.id}">{$product_attribute.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="flex__container flex__start">
            <label for="valueId" class="margin__right-1">{$LANG.valueUc}:</label>
            <input type="text" name="value" id="valueId" size="50" tabindex="20"
                       value="{$product_attribute_values.value}"/>
        </div>
        <div class="flex__container flex__start">
            <label for="" class="margin__right-1">{$LANG.status}:</label>
            {html_options name=enabled id=enabledId options=$enabled selected=$product_attribute_values.enabled tabindex=30}
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
