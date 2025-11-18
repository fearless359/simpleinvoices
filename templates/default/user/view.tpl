{*
 *  Script: view.tpl
 *      User details template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface. 
 * 	    20210701 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.username}:</div>
        <div>{$user.username|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.password}:</div>
        <div>**********</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.role}:</div>
        <div>{$user.role_name|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.email}:</div>
        <div>{$user.email|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$user.enabled_text|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.userId}:</div>
        <div>{$user_id_desc|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=user&amp;view=edit&amp;id={$user.id|urlEncode}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=user&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
