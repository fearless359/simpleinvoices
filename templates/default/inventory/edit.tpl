<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=inventory&amp;view=save&amp;id={$inventory.id|urlencode}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.dateUc}:</th>
                <td>
                    <input type="text" class="si_input validate[required,custom[date],length[0,10]] date-picker"
                           name="date" id="date" size="10" tabindex="10"
                           value="{if isset($inventory.date)}{$inventory.date|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.productUc}:</th>
                <td>
                    <select name="product_id" class="si_input validate[required] product_inventory_change" tabindex="20">
                        <option value=''></option>
                        {foreach $product_all as $product}
                            <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                                    {if $product.id == $inventory.product_id}selected{/if} >{$product.description|htmlSafe}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.quantity}:</th>
                <td>
                    <input name="quantity" class="si_input validate[required]" size="10" tabindex="30"
                           value='{$inventory.quantity|utilNumberTrim}'>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.costUc}:</th>
                <td>
                    <input id="cost" name="cost" class="si_input validate[required]" size="10" tabindex="40"
                           value='{$inventory.cost|utilNumber}'>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.notes}:</th>
                <td>
                    <input name="note" id="note" {if isset($inventory.note)}value="{$inventory.note|outHtml}"{/if} type="hidden">
                    <trix-editor input="note" class="si_input" tabindex="50"></trix-editor>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
                <img class="button_img" src="images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=inventory&amp;view=manage" class="negative" tabindex="60">
                <img src="images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
