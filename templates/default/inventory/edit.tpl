{*
 *  Script: edit.tpl
 *      Inventory update template
 *
 *  Last Modified:
 *      20210630 by Rich Rowley to use grid layout rather than tables.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=inventory&amp;view=save&amp;id={$inventory.id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="date" class="cols__3-span-1">{$LANG.dateUc}:</label>
            <input type="text" name="date" id="date" size="10" tabindex="10"
                   class="cols__4-span-6 validate[required,custom[date],length[0,10]] date-picker"
                   value="{if isset($inventory.date)}{$inventory.date|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="productId" class="cols__3-span-1">{$LANG.productUc}:</label>
            <select name="product_id" id="productId" class="cols__4-span-6 validate[required] product_inventory_change" tabindex="20">
                <option value=''></option>
                {foreach $product_all as $product}
                    <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                            {if $product.id == $inventory.product_id}selected{/if} >{$product.description|htmlSafe}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="quantityId" class="cols__3-span-1">{$LANG.quantity}:</label>
            <input name="quantity" id="quantityId" class="cols__4-span-6 validate[required]" size="10" tabindex="30"
                   value='{$inventory.quantity|utilNumberTrim}'>
        </div>
        <div class="grid__container grid__head-10">
            <label for="cost" class="cols__3-span-1">{$LANG.costUc}:</label>
            <input name="cost" id="cost" class="cols__4-span-6 validate[required]" size="10" tabindex="40"
                   value='{$inventory.cost|utilNumber}'>
        </div>
        <div class="grid__container grid__head-10">
            <label for="" class="cols__3-span-1">{$LANG.notes}:</label>
            <div class="cols__4-span-6">
                <input name="note" id="note" {if isset($inventory.note)}value="{$inventory.note|outHtml}"{/if} type="hidden">
                <trix-editor input="note" tabindex="50"></trix-editor>
            </div>
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=inventory&amp;view=manage" class="button negative" tabindex="60">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
