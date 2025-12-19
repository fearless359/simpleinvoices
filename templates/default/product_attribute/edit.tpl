{*
 *  Script: edit.tpl
 *      Product Attributes update template
 *
 *  Last edited:
 *      20251217 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}">
        <div class="row">
            <div class="col__20">
                <label for="nameId" class="margin__right-1">{$LANG.nameUc}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="name" id="nameId" required size="50" tabindex="10"
                       value="{if isset($product_attribute.name)}{$product_attribute.name}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="typeId" class="margin__right-1">{$LANG.type}:</label>
            </div>
            <div class="col__80">
                <select name="type_id" id="typeId" tabindex="20">
                    {foreach $types as $k => $v}
                        <option value="{if isset($v.id)}{$v.id}{/if}"
                                {if $product_attribute.type_id == $v.id}selected{/if}>{$LANG[$v.name]|capitalize}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="enabledId" class="margin__right-1">{$LANG.enabled}:</label>
            </div>
            <div class="col__80">
                {html_options name=enabled id=enabledId options=$enabled selected=$product_attribute.enabled tabindex=30}
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="visibleId" class="margin__right-1">{$LANG.visible}:</label>
            </div>
            <div class="col__80">
                {html_options name=visible id=visibleId options=$enabled selected=$product_attribute.visible tabindex=40}
            </div>
        </div>
        <div class="align__text-center margin__top-3 margin__bottom-2">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=product_attribute&amp;view=manage" class="button negative" tabindex="60">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
    </form>
</div>
