{*
 *  Script: details.tpl
 *      Custom flags details template
 *
 *  Last edited:
 *      20251207 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210619 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=custom_flags&amp;view=save&amp;associated_table={$cflg.associated_table|urlEncode}&amp;flg_id={$cflg.flg_id|urlEncode}">
        <div class="row">
            <div class="col__20">
                <span class="label">{$LANG.associatedTable}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsAssociatedTable}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </span>
            </div>
            <div class="col__80">
                <span class="inputText">{$cflg.associated_table|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <span class="label">{$LANG.flagNumber}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsFlagNumber}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </span>
            </div>
            <div class="col__80">
                <span class="inputText">{$cflg.flg_id|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="fieldLabelId">{$LANG.fieldLabelUc}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsFieldLabel}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__80">
                <input type="text" name="{$LANG.fieldLabelUc|lower}" id="fieldLabelId" autofocus
                       value="{if isset($cflg.field_label)}{$cflg.field_label|escape}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="enabledId">{$LANG.customFlagsUc}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsEnable}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </label>
            </div>
            <div class="col__80">
                {html_options name=$LANG.enabled|lower id=enabledId options=$enable_options selected=$cflg.enabled|htmlSafe}
            </div>
        </div>
        <div class="row">
            <div class="col__20 float__right">
                <input type="checkbox" id="clearCustomFlagsId" name="clear_custom_flags_{$cflg.flg_id}" value="1"/>
                <span class="margin__top-0-2">&nbsp;</span>
            </div>
            <div class="col__75">
                <label for="clearCustomFlagsId">:&nbsp;{$LANG.resetCustomFlags}
                    <img class="tooltip" title="{$LANG.helpResetCustomFlagsProducts}"
                         src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col__100">
                <span class="label">{$LANG.fieldHelpUc}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsFieldHelp}" src="{$helpImagePath}help-small.png"
                         alt=""/>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col__100">
                <input type="hidden" id="fieldHelpId" name="field_help" value="{$cflg.field_help}"/>
                <trix-editor class="trix-content" input="fieldHelpId"></trix-editor>

            </div>

            <div class="align__text-center margin__top-2">
                <button type="submit" class="positive" name="save_custom_flag" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=custom_flags&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit">
        <input type="hidden" name="associated_table"
               value="{if isset($cflg.associated_table)}{$cflg.associated_table|htmlSafe}{/if}"/>
        <input type="hidden" name="flg_id" value="{if isset($cflg.flg_id)}{$cflg.flg_id|htmlSafe}{/if}"/>
    </form>
</div>
