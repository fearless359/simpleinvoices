{*
 *  Script:edit.tpl
 *      Inventory update template
 *
 *  Last Modified:
 *      20251215 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface. 
 *      20210630 by Rich Rowley to use grid layout rather than tables.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=inventory&amp;view=save&amp;id={$inventory.id|urlEncode}">
        <div class="row">
            <div class="col__20">
                <label for="date" class="margin__right-1">{$LANG.dateUc}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="date" id="date" size="10" tabindex="10" required readonly class="date-picker"
                       value="{if isset($inventory.date)}{$inventory.date|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="productId" class="margin__right-1">{$LANG.productUc}:</label>
            </div>
            <div class="col__80">
                <select name="product_id" id="productId" class="productInventoryChange" required tabindex="20">
                    <option value=''></option>
                    {foreach $product_all as $product}
                        <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                                {if $product.id == $inventory.product_id}selected{/if} >{$product.description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$inventory.locale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$inventory.currency_code}">
        <div class="row">
            <div class="col__20">
                <label for="quantityId" class="margin__right-1">{$LANG.quantityUc}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="quantity" id="quantityId" class="validateWholeNumber" required size="10"
                       tabindex="30"
                       value='{$inventory.quantity|utilNumberTrim:0}'>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="cost" class="margin__right-1">{$LANG.costUc}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="cost" id="cost" class="validateNumber" required size="10" tabindex="40"
                       value='{$inventory.cost|utilNumber}'>
            </div>
        </div>
        <div class="row">
            <div class="col__100">
                <label for="note">{$LANG.notes}:</label>
            </div>
        </div>
        <div class="row">
            <div class="col__100">
                <input name="note" id="note" {if isset($inventory.note)}value="{$inventory.note|outHtml}"{/if}
                       type="hidden">
                <trix-editor input="note" tabindex="50"></trix-editor>
            </div>
        </div>
        <div class="align__text-center margin__top-3 margin__bottom-2">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=inventory&amp;view=manage" class="button negative" tabindex="60">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
    </form>
</div>
