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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlencode}" >
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
                <img src="images/common/tick.png" alt="{$LANG.edit}"/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=custom_fields&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
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
                               value="{if isset($cf.cf_custom_label)}{$cf.cf_custom_label|htmlsafe}{/if}"/></td>
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
                <a href="index.php?module=custom_fields&amp;view=manage" class="negative">
                    <img src="images/common/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="cf_custom_field" value="{$cf.cf_custom_field}"/>
        <input type="hidden" name="op" value="edit">
    {/if}
</form>
{literal}
<script>
    $(document).ready(function() {
        $('#cf_custom_label_maint').change(function () {
            if (!$(this).val()) {
                $('#clear_data_option').attr('disabled');
            } else {
                $('#clear_data_option').removeAttr('disabled');
            }
        });
    });
</script>
{/literal}