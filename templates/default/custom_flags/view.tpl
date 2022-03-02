{*
 *  Script: view.tpl
 *      Custom flags view template
 *
 *  Last edited:
 *      20210619 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="grid__area">
    <div class="grid__container grid__head-6">
        <div class="cols__2-span-1 bold align__text-right margin__right-1">{$LANG.associatedTable}:</div>
        <div class="cols__3-span-4">{$cflg.associated_table|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__2-span-1 bold align__text-right margin__right-1">{$LANG.flagNumber}:</div>
        <div class="cols__3-span-4">{$cflg.flg_id|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__2-span-1 bold align__text-right margin__right-1">{$LANG.fieldLabelUc}:</div>
        <div class="cols__3-span-4">{$cflg.field_label|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__2-span-1 bold align__text-right margin__right-1">{$LANG.enabled}:</div>
        <div class="cols__3-span-4">{$cflg.enabled_text|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__2-span-1 bold align__text-right margin__right-1">{$LANG.fieldHelpUc}:</div>
        <div class="cols__3-span-4">{$cflg.field_help|outHtml}</div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=custom_flags&amp;view=edit&amp;associated_table={$cflg.associated_table|urlencode}&flg_id={$cflg.flg_id|urlencode}"
       class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=custom_flags&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
