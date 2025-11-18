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
        <div class="bold margin__right-1">{$LANG.descriptionUc}:</div>
        <div>{$paymentType.pt_description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="bold margin__right-1">{$LANG.status}:</div>
        <div>{$paymentType.enabled_text|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=payment_types&amp;view=edit&amp;id={$paymentType.pt_id}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=payment_types&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
