{*
 *  Script: edot.tpl
 * 	    Custom fields edit template
 *
 *  Last Modified:
 *      20210618 by Rich Rowley to convert to grid layout.
 *      20180922 by Rich Rowley to add option to clean up when field cleared.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *	    GPL v3 or above
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlEncode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-3 bold align__text-right margin__right-1">{$LANG.customFieldDbFieldName}:</div>
            <div class="cols__6-span-5">{$cf.cf_custom_field|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-3 bold align__text-right margin__right-1">{$LANG.customField}:</div>
            <div class="cols__6-span-5">{$cf.name|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="cfCustomLabelId" class="cols__3-span-3 align__text-right margin__right-1">{$LANG.customLabel}:</label>
            <input type="text" name="cfLabel" id="cfCustomLabelId" class="cols__6-span-3" autofocus tabindex="10"
                   value="{if isset($cf.cf_custom_label)}{$cf.cf_custom_label|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="clearDataId" class="cols__3-span-3 align__text-right margin__right-1">{$LANG.clearData}:
                <img class="tooltip" title="{$LANG.helpResetCustomFlagsProducts}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="checkbox" name="clear_data" id="clearDataId" value="yes" disabled
                   class="cols__6-span-5 margin__top-0-75" tabindex="20"/>
        </div>
    </div>
    <br/>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_custom_field" value="{$LANG.save}" tabindex="30">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=custom_fields&amp;view=manage" class="button negative" tabindex="40">
            <img src="images/cross.png" alt=""/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="cf_custom_field" value="{$cf.cf_custom_field}"/>
    <input type="hidden" name="op" value="edit">
</form>
{literal}
    <script>
        $(document).ready(function () {
            $('#cfCustomLabelId').change(function () {
                if (!$(this).val()) {
                    $('#clearDataId').attr('disabled');
                } else {
                    $('#clearDataId').removeAttr('disabled');
                }
            });
        });
    </script>
{/literal}
