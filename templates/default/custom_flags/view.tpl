{*
 *  Script: view.tpl
 *      Custom flags view template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210619 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.associatedTable}:</div>
        <div>{$cflg.associated_table|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.flagNumber}:</div>
        <div>{$cflg.flg_id|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.fieldLabelUc}:</div>
        <div>{$cflg.field_label|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$cflg.enabled_text|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.fieldHelpUc}:</div>
        <div>{$cflg.field_help|outHtml}</div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=custom_flags&amp;view=edit&amp;associated_table={$cflg.associated_table|urlEncode}&flg_id={$cflg.flg_id|urlEncode}"
       class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=custom_flags&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
