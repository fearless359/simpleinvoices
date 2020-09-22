{*
 * Script: details.tpl
 * Custom flags details template
 *
 * License:
 *  GPL v3 or above
 *}
<div class="si_form">
    <div class="si_cust_info">
        <table>
            <tr>
                <th class="details_screen">{$LANG.associatedTable}:</th>
                <td>{$cflg.associated_table|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.flagNumber}:</th>
                <td>{$cflg.flg_id|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.fieldLabelUc}:</th>
                <td>{$cflg.field_label|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.enabled}:</th>
                <td>{$cflg.enabled_text|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.fieldHelpUc}:</th>
                <td>{$cflg.field_help|htmlSafe}</td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=custom_flags&amp;view=edit&amp;associated_table={$cflg.associated_table|urlencode}&flg_id={$cflg.flg_id|urlencode}"
           class="positive">
            <img src="../../../images/report_edit.png" alt=""/>
            {$LANG.edit}
        </a>
        <a href="index.php?module=custom_flags&amp;view=manage" class="negative">
            <img src="../../../images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
</div>
