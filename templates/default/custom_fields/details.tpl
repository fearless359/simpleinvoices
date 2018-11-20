{*
* Script: details.tpl
* 	Custom fields details template
*
 * Last Modified:
 * 20180922 by Rich Rowley to add option to clean up when field cleared.
 *
* Website:
 *    https://simpleinvoices.group
 *
* License:
*	 GPL v3 or above
*}
{* Note that frmpost_Validator() is generated at runtime using the jsFormValidationBegin() function*}
<form name="frmpost" action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlencode}"
      method="POST" onsubmit="return frmpost_Validator(this);">
    {if $smarty.get.action == "view" }
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th>{$LANG.id}</th>
                    <td>{$cf.cf_id|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_field_db_field_name}</th>
                    <td>{$cf.cf_custom_field|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_field}</th>
                    <td>{$cf.name|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_label}</th>
                    <td>{$cf.cf_custom_label|htmlsafe}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=custom_fields&amp;view=details&amp;id={$cf.cf_id|urlencode}&amp;action=edit" class="positive">
                <img src="images/common/tick.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action == "edit" }
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.id}</th>
                    <td>{$cf.cf_id|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_field_db_field_name}</th>
                    <td>{$cf.cf_custom_field|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_field}</th>
                    <td>{$cf.name|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.custom_label}</th>
                    <td><input type="text" name="cf_custom_label" id="cf_custom_label_maint" size="25" autofocus
                               value="{$cf.cf_custom_label|htmlsafe}"/></td>
                </tr>
                <tr>
                    <th>
                        {$LANG.clear_data}
                        <a class="cluetip" title="{$LANG.reset_custom_flags}" tabindex="-1"
                           href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_reset_custom_flags_products">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="checkbox" name="clear_data" id="clear_data_option" value="yes" disabled/></td>
                </tr>
            </table>

            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_custom_field" value="{$LANG.save}">
                    <img class="button_img" src="images/common/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <button type="submit" class="negative" name="cancel" value="{$LANG.cancel}">
                    <img class="button_img" src="images/common/cross.png" alt=""/>
                    {$LANG.cancel}
                </button>
            </div>
        </div>
        <input type="hidden" name="cf_custom_field" value="{$cf.cf_custom_field}"/>
        <input type="hidden" name="op" value="edit_custom_field">
    {/if}
</form>
