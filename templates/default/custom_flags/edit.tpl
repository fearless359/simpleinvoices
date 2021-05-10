{*
 * Script: details.tpl
 * Custom flags details template
 *
 * License:
 *  GPL v3 or above
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=custom_flags&amp;view=save&amp;associated_table={$cflg.associated_table|urlencode}&amp;flg_id={$cflg.flg_id|urlencode}">
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">
                        {$LANG.associatedTable}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFlagsAssociatedTable"
                           title="{$LANG.associatedTable}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$cflg.associated_table|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">
                        {$LANG.flagNumber}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFlagsFlagNumber"
                           title="{$LANG.flagNumber}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td class="si_input">{$cflg.flg_id|htmlSafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">
                        {$LANG.fieldLabelUc}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFlagsFieldLabel"
                           title="{$LANG.fieldLabelUc}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="{$LANG.fieldLabelUc|lower}" class="si_input" size="20"
                               value="{if isset($cflg.field_label)}{$cflg.field_label|escape}{/if}"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customFlagsUc}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFlagsEnable"
                           title="{$LANG.customFlagsUc}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td style="float:left;margin-left:auto;width:10px;">
                        {html_options name=$LANG.enabled|lower class=si_input options=$enable_options selected=$cflg.enabled|htmlSafe}
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">
                        {$LANG.resetCustomFlags}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpResetCustomFlagsProducts"
                           title="{$LANG.resetCustomFlags}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="checkbox" name="clear_custom_flags_{$cflg.flg_id}" class="si_input" value="1"/></td>
                </tr>
                <tr>
                    <th class="details_screen">
                        {$LANG.fieldHelpUc}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpCustomFlagsFieldHelp"
                           title="{$LANG.fieldHelpUc}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input name="{$LANG.fieldHelpUc|lower}" id="{$LANG.fieldHelpUc|lower}" {if isset($cflg.field_help)}value="{$cflg.field_help|outHtml}"{/if} type="hidden">
                        <trix-editor input="{$LANG.fieldHelpUc|lower}" class="si_input"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_custom_flag" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=custom_flags&amp;view=manage" class="negative">
                    <img src="images/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit">
        <input type="hidden" name="associated_table" value="{if isset($cflg.associated_table)}{$cflg.associated_table|htmlSafe}{/if}"/>
        <input type="hidden" name="flg_id" value="{if isset($cflg.flg_id)}{$cflg.flg_id|htmlSafe}{/if}"/>
</form>
