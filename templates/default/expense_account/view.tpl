{*
 *  Script: view.tpl
 *      Expense Account detail template
 *
 *  Last edited:
 *      20210622 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="grid__area">
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold">{$LANG.nameUc}:</div>
        <div class="cols__4-span-7">{$expense_account.name}</div>
    </div>
</div>
<div class="align__text-center margin__top-2 margin__bottom-2">
    <a href="index.php?module=expense_account&amp;view=edit&amp;id={$expense_account.id}" class="button positive">
        <img src="images/add.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=expense_account&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
