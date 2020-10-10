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
<div class="si_form">
    <div class="si_cust_info">
        <table>
            <tr>
                <th class="details_screen">{$LANG.idUc}:</th>
                <td>{$cf.cf_id|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customFieldDbFieldName}:</th>
                <td>{$cf.cf_custom_field|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customField}:</th>
                <td>{$cf.name|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customLabel}:</th>
                <td>{$cf.cf_custom_label|htmlSafe}</td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=custom_fields&amp;view=edit&amp;id={$cf.cf_id|urlencode}" class="positive">
            <img src="../../../images/tick.png" alt="{$LANG.edit}"/>
            {$LANG.edit}
        </a>
        <a href="index.php?module=custom_fields&amp;view=manage" class="negative">
            <img src="../../../images/cross.png" alt="{$LANG.cancel}"/>
            {$LANG.cancel}
        </a>
    </div>
</div>
