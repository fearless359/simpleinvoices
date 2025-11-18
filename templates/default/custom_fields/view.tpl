{*
 *  Script: details.tpl
 *      Custom fields details template
 *
 *  Last Modified:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210618 by Rich Rowley to use grid layout.
 *      20180922 by Rich Rowley to add option to clean up when field cleared.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.customFieldDbFieldName}:</div>
        <div>{$cf.cf_custom_field|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.customField}:</div>
        <div>{$cf.name|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.customLabel}:</div>
        <div>{$cf.cf_custom_label|htmlSafe}</div>
    </div>
    <br/>
    <div class="align__text-center">
        <a href="index.php?module=custom_fields&amp;view=edit&amp;id={$cf.cf_id|urlEncode}" class="button positive">
            <img src="images/tick.png" alt="{$LANG.edit}"/>{$LANG.edit}
        </a>
        <a href="index.php?module=custom_fields&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
</div>
