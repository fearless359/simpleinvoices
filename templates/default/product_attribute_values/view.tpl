{*
 *  Script: view.tpl
 *      Product Attribute Values details template
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.attribute}:</div>
        <div class="cols__6-span-2">{$product_attribute_values.name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.value}:</div>
        <div class="cols__6-span-1">{$product_attribute_values.value|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.enabled}:</div>
        <div class="cols__6-span-1">{$product_attribute_values.enabled_text}</div>
    </div>
</div>
<div class="align__text-center margin__top-2">
    <a href="index.php?module=product_attribute_values&amp;view=edit&amp;id={$product_attribute_values.id|htmlSafe}"
       class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=product_attribute_values&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
