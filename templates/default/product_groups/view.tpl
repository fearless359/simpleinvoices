{*
 *  Script: view.tpl
 *      Product Group details template
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
        <div class="cols__4-span-1 bold">{$LANG.nameUc}:</div>
        <div class="cols__5-span-5">{$productGroup.name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-1 bold">{$LANG.markupUc}%:</div>
        <div class="cols__5-span-1">{$productGroup.markup}%</div>
    </div>
</div>
<div class="align__text-center margin__top-2">
    <a href="index.php?module=product_groups&amp;view=edit&amp;name={$productGroup.name|htmlSafe}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=product_groups&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
