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
<div class="flex__area margin__bottom-3">
    <div class="flex__container">
        <div class="bold margin__right-1">{$LANG.nameUc}:</div>
        <div>{$expense_account.name}</div>
    </div>
    <div class="flex__container margin__top-2 margin__bottom-2">
        <a href="index.php?module=expense_account&amp;view=edit&amp;id={$expense_account.id}" class="button positive">
            <img src="images/add.png" alt="{$LANG.edit}"/>{$LANG.edit}
        </a>
        <a href="index.php?module=expense_account&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
</div>
