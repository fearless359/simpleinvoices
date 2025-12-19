{*
 *  Script: edot.tpl
 * 	    Custom fields edit template
 *
 *  Last Modified:
 *      20251207 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210618 by Rich Rowley to convert to grid layout.
 *      20180922 by Rich Rowley to add option to clean up when field cleared.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *	    GPL v3 or above
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlEncode}">
        <div class="row">
            <div class="col__20">
                <span class="label">{$LANG.customFieldDbFieldName}:</span>
            </div>
            <div class="col__80">
                <span class="inputText">{$cf.cf_custom_field|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <span class="label">{$LANG.customField}:</span>
            </div>
            <div class="col__80">
                <span class="inputText">{$cf.name|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="cfCustomLabelId">{$LANG.customLabel}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="cfLabel" id="cfCustomLabelId" autofocus tabindex="10"
                       value="{if isset($cf.cf_custom_label)}{$cf.cf_custom_label|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="clearDataId">{$LANG.clearData}:
                    <img class="tooltip" title="{$LANG.helpResetCustomFlagsProducts}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__80">
                {* Field enabled when the cfCustomeLabelId field is modified *}
                <input type="checkbox" name="clear_data" id="clearDataId" value="yes" disabled tabindex="20"/>
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
</div>
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
