{*
 *  Script: view.tpl
 *      Payment type details template
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
        <div class="cols__3-span-2 bold">{$LANG.descriptionUc}:</div>
        <div class="cols__5-span-5">{$paymentType.pt_description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-2 bold">{$LANG.status}:</div>
        <div class="cols__5-span-1">{$paymentType.enabled_text|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-2">
    <a href="index.php?module=payment_types&amp;view=edit&amp;id={$paymentType.pt_id}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=payment_types&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
