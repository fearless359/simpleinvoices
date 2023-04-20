{*
 * Script: details.tpl
 * Custom flags details template
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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=custom_flags&amp;view=save&amp;associated_table={$cflg.associated_table|urlEncode}&amp;flg_id={$cflg.flg_id|urlEncode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.associatedTable}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsAssociatedTable}" src="{$helpImagePath}help-small.png" alt=""/>
            </div>
            <div class="cols__4-span-7">{$cflg.associated_table|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-2 bold align__text-right margin__right-1">{$LANG.flagNumber}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsFlagNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            </div>
            <div class="cols__4-span-7">{$cflg.flg_id|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="fieldLabelId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.fieldLabelUc}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsFieldLabel}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <input type="text" name="{$LANG.fieldLabelUc|lower}" id="fieldLabelId" autofocus class="cols__4-span-4"
                   value="{if isset($cflg.field_label)}{$cflg.field_label|escape}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.customFlagsUc}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsEnable}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            {html_options name=$LANG.enabled|lower id=enabledId class="cols__4-span-2" options=$enable_options selected=$cflg.enabled|htmlSafe}
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__4-span-6">
                <div class="grid__container grid__head-checkbox">
                    <input type="checkbox" name="clear_custom_flags_{$cflg.flg_id}" id="clearCustomFlagsId" value="1"
                           class="margin__top-0-75" />
                    <label for="clearCustomFlagsId">{$LANG.resetCustomFlags}:
                        <img class="tooltip" title="{$LANG.helpResetCustomFlagsProducts}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-2 margin__bottom-1">
                <label for="{$LANG.fieldHelpUc|lower}">{$LANG.fieldHelpUc}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsFieldHelp}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="cols__2-span-8">
                <input name="{$LANG.fieldHelpUc|lower}" id="{$LANG.fieldHelpUc|lower}"
                       {if isset($cflg.field_help)}value="{$cflg.field_help|outHtml}"{/if} type="hidden">
                <trix-editor input="{$LANG.fieldHelpUc|lower}"></trix-editor>
            </div>
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
    <input type="hidden" name="associated_table" value="{if isset($cflg.associated_table)}{$cflg.associated_table|htmlSafe}{/if}"/>
    <input type="hidden" name="flg_id" value="{if isset($cflg.flg_id)}{$cflg.flg_id|htmlSafe}{/if}"/>
</form>
