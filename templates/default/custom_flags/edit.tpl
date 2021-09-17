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
      action="index.php?module=custom_flags&amp;view=save&amp;associated_table={$cflg.associated_table|urlencode}&amp;flg_id={$cflg.flg_id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-2 bold">{$LANG.associatedTable}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsAssociatedTable}" src="{$helpImagePath}help-small.png" alt=""/>
            </div>
            <div class="cols__4-span-7">{$cflg.associated_table|htmlSafe}</div>

            <div class="cols__2-span-2 bold margin__top-1">{$LANG.flagNumber}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsFlagNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            </div>
            <div class="cols__4-span-7 margin__top-1">{$cflg.flg_id|htmlSafe}</div>

            <label for="fieldLabelId" class="cols__2-span-2 bold margin__top-1">{$LANG.fieldLabelUc}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsFieldLabel}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__4-span-7 margin__top-1">
                <input type="text" name="{$LANG.fieldLabelUc|lower}" id="fieldLabelId" autofocus
                       value="{if isset($cflg.field_label)}{$cflg.field_label|escape}{/if}"/>
            </div>

            <label for="enabledId" class="cols__2-span-2">{$LANG.customFlagsUc}:
                <img class="tooltip" title="{$LANG.helpCustomFlagsEnable}" src="{$helpImagePath}help-small.png" alt=""/>
            </label>
            <div class="cols__4-span-7 margin__top-1">
                <div class="grid__container grid__head-10">
                    {html_options name=$LANG.enabled|lower id=enabledId class=cols__1-span-2
                    options=$enable_options selected=$cflg.enabled|htmlSafe}
                    <div class="cols__3-span-8">
                        <div class="grid__container grid__head-2">
                            <input type="checkbox" name="clear_custom_flags_{$cflg.flg_id}" id="clearCustomFlagsId"
                                   class="cols__1-span-1 align__right margin__top-0-75" value="1"/>
                            <label for="clearCustomFlagsId" class="cols__2-span-1">
                                {$LANG.resetCustomFlags}:
                                <img class="tooltip" title="{$LANG.helpResetCustomFlagsProducts}" src="{$helpImagePath}help-small.png" alt=""/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cols__2-span-2">
                <label for="{$LANG.fieldHelpUc|lower}">{$LANG.fieldHelpUc}:
                    <img class="tooltip" title="{$LANG.helpCustomFlagsFieldHelp}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="cols__2-span-9">
                <input name="{$LANG.fieldHelpUc|lower}" id="{$LANG.fieldHelpUc|lower}"
                       {if isset($cflg.field_help)}value="{$cflg.field_help|outHtml}"{/if} type="hidden">
                <trix-editor input="{$LANG.fieldHelpUc|lower}"></trix-editor>
            </div>
        </div>
        <div class="align__text-center">
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
