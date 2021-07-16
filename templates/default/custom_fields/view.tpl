{*
 *  Script: details.tpl
 *      Custom fields details template
 *
 *  Last Modified:
 *      20210618 by Rich Rowley to use grid layout.
 *      20180922 by Rich Rowley to add option to clean up when field cleared.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-3 bold">{$LANG.idUc}:</div>
        <div class="cols__7-span-3">{$cf.cf_id|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-3 bold">{$LANG.customFieldDbFieldName}:</div>
        <div class="cols__7-span-3">{$cf.cf_custom_field|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-3 bold">{$LANG.customField}:</div>
        <div class="cols__7-span-3">{$cf.name|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-3 bold">{$LANG.customLabel}:</div>
        <div class="cols__7-span-3">{$cf.cf_custom_label|htmlSafe}</div>
    </div>
    <br/>
    <div class="align__text-center">
        <a href="index.php?module=custom_fields&amp;view=edit&amp;id={$cf.cf_id|urlencode}" class="button positive">
            <img src="images/tick.png" alt="{$LANG.edit}"/>{$LANG.edit}
        </a>
        <a href="index.php?module=custom_fields&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
</div>
