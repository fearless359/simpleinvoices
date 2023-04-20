{*
 *  Script: create.tpl
 *      Inventory add template
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
{* If one required field is present then all are. So only need to test the one. *}
{if !empty($smarty.post.product_id)}
    {include file="templates/default/inventory/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=inventory&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="date" class="cols__2-span-1 align__text-right">{$LANG.dateUc}:&nbsp;</label>
                <input type="text" name="date" id="date" required readonly class="cols__3-span-1 date-picker" size="10" tabindex="10"
                       value="{if !empty($smarty.post.date)}{$smarty.post.date}{else}{'now'|date_format:'%Y-%m-%d'}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="productId" class="cols__2-span-1 align__text-right">{$LANG.productUc}:&nbsp;</label>
                <select name="product_id" id="productId" class="cols__3-span-4 productInventoryChange" required tabindex="20">
                    <option value=''></option>
                    {foreach $product_all as $product}
                        <option value="{$product.id|htmlSafe}"
                                {if isset($smarty.post.product_id) && $smarty.post.product_id == $product.id}selected{/if}>{$product.description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <div class="grid__container grid__head-10">
                <label for="quantityId" class="cols__2-span-1 align__text-right">{$LANG.quantityUc}:&nbsp;</label>
                <input name="quantity" id="quantityId" class="cols__3-span-1 validateWholeNumber" required size="10" tabindex="30"
                       {if !empty($smarty.post.quantity)}value="{$smarty.post.quantity|utilNumberTrim:0}"{/if}>
            </div>
            <div class="grid__container grid__head-10">
                <label for="cost" class="cols__2-span-1 align__text-right">{$LANG.costUc}:&nbsp;</label>
                <input name="cost" id="cost" class="cols__3-span-2 validateNumber" required size="10" tabindex="40"
                       {if !empty($smarty.post.cost)}value="{$smarty.post.cost}"{/if}>
            </div>
            <div class="grid__container grid__head-10">
                <label for="note" class="cols__2-span-1">{$LANG.notes}:&nbsp;</label>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-8">
                    <input name="note" id="note" type="hidden" {if isset($smarty.post.note)}value="{$smarty.post.note|outHtml}"{/if}>
                    <trix-editor input="note" tabindex="50"></trix-editor>
                </div>
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="60">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=inventory&amp;view=manage" class="button negative" tabindex="70">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
