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
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.id}:</th>
                <td class="si_input">{$cf.cf_id|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customFieldDbFieldName}:</th>
                <td class="si_input">{$cf.cf_custom_field|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customField}:</th>
                <td class="si_input">{$cf.name|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customLabel}:</th>
                <td>
                    <input type="text" name="si_input" id="cf_custom_label_maint" size="25" autofocus tabindex="10"
                           value="{if isset($cf.cf_custom_label)}{$cf.cf_custom_label|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.clearData}:
                    <a class="cluetip" title="{$LANG.resetCustomFlags}" tabindex="-1"
                       href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpResetCustomFlagsProducts">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td><input type="checkbox" name="clear_data" id="clear_data_option" class="si_input" value="yes" disabled tabindex="20"/></td>
            </tr>
        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_custom_field" value="{$LANG.save}" tabindex="30">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=custom_fields&amp;view=manage" class="negative" tabindex="40">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="cf_custom_field" value="{$cf.cf_custom_field}"/>
    <input type="hidden" name="op" value="edit">
</form>
{literal}
    <script>
        $(document).ready(function () {
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
