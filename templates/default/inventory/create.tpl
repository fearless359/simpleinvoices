{*
 *  Script: create.tpl
 *      Inventory add template
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
{* If one required field is present then all are. So only need to test the one. *}
{if !empty($smarty.post.product_id)}
    {include file="templates/default/inventory/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=inventory&amp;view=create">
            <div class="row">
                <div class="col__25">
                    <label for="date" class="margin__right-1">{$LANG.dateUc}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="date" id="date" required readonly class="date-picker" size="10"
                           tabindex="10"
                           value="{if !empty($smarty.post.date)}{$smarty.post.date}{else}{'now'|date_format:'%Y-%m-%d'}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="productId" class="margin__right-1">{$LANG.productUc}:</label>
                </div>
                <div class="col__75">
                    <select name="product_id" id="productId" class="productInventoryChange" required tabindex="20">
                        <option value=''></option>
                        {foreach $product_all as $product}
                            <option value="{$product.id|htmlSafe}"
                                    {if isset($smarty.post.product_id) && $smarty.post.product_id == $product.id}selected{/if}>{$product.description|htmlSafe}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <div class="row">
                <div class="col__25">
                    <label for="quantityId" class="margin__right-1">{$LANG.quantityUc}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="quantity" id="quantityId" class="validateWholeNumber" required size="10" tabindex="30"
                           {if !empty($smarty.post.quantity)}value="{$smarty.post.quantity|utilNumberTrim:0}"{/if}>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="cost" class="margin__right-1">{$LANG.costUc}:</label>
                </div>
                <div class="col__75">
                    <input type="text" name="cost" id="cost" class="validateNumber" required size="10" tabindex="40"
                           {if !empty($smarty.post.cost)}value="{$smarty.post.cost}"{/if}>
                </div>
            </div>
            <div class="row">
                <div class="col__25 align__text-left">
                    <label for="note">{$LANG.notes}:</label>
                </div>
            </div>
            <div class="row">
                <div class="col__100">
                    <input name="note" id="note" type="hidden"
                           {if isset($smarty.post.note)}value="{$smarty.post.note|outHtml}"{/if}>
                    <trix-editor input="note" tabindex="50"></trix-editor>
                </div>
            </div>
            <div class="align__text-center margin__top-3 margin__bottom-2">
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
    </div>
{/if}
