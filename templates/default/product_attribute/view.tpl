{*
 *  Script: view.tpl
 *      Payment type details template
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
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.nameUc}:</div>
        <div>{$product_attribute.name}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.type}:</div>
        <div>{$product_attribute.typeName|capitalize|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$product_attribute.enabledText|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.visible}:</div>
        <div>{$product_attribute.visibleText|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-2">
    <a href="index.php?module=product_attribute&amp;view=edit&amp;id={$product_attribute.id|htmlSafe}"
       class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=product_attribute&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
