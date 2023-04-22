{*
 *  Script: view.tpl
 *      User details template
 *
 *  Last edited:
 * 	    20210701 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.username}:</div>
        <div class="cols__4-span-5">{$user.username|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.password}:</div>
        <div class="cols__4-span-5">**********</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.role}:</div>
        <div class="cols__4-span-5">{$user.role_name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.email}:</div>
        <div class="cols__4-span-5">{$user.email|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.enabled}:</div>
        <div class="cols__4-span-5">{$user.enabled_text|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.userId}:</div>
        <div class="cols__4-span-5">{$user_id_desc|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=user&amp;view=edit&amp;id={$user.id|urlencode}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=user&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
