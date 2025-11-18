{*
 *  Script: view.tpl
 *      Product Attribute Values details template
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
        <div class="bold margin__right-1">{$LANG.attribute}:</div>
        <div>{$product_attribute_values.name|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.valueUc}:</div>
        <div>{$product_attribute_values.value|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$product_attribute_values.enabled_text}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-3">
    <a href="index.php?module=product_attribute_values&amp;view=edit&amp;id={$product_attribute_values.id|htmlSafe}"
       class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=product_attribute_values&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
