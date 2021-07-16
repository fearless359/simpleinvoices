{*
 *  Script: view.tpl
 *      Inventory details template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.productUc}:</div>
        <div class="cols__6-span-3">{$inventory.description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.dateUc}:</div>
        <div class="cols__6-span-3">{$inventory.date|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.quantity}:</div>
        <div class="cols__6-span-3">{$inventory.quantity|utilNumberTrim}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.costUc}:</div>
        <div class="cols__6-span-3">{$inventory.cost|utilNumber}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.notes}:</div>
        <div class="cols__6-span-3">{$inventory.note}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=inventory&amp;view=edit&amp;id={$inventory.id|htmlSafe}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=inventory&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
