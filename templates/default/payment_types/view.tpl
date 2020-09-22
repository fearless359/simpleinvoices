{*
 *  Script: details.tpl
 *      Payment type details template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group *}
<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.descriptionUc}:</th>
            <td>{$paymentType.pt_description|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.status}:</th>
            <td>{$paymentType.enabled_text|htmlSafe}</td>
        </tr>
    </table>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=payment_types&amp;view=edit&amp;id={$paymentType.pt_id}" class="positive">
        <img src="../../../images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=payment_types&amp;view=manage" class="negative">
        <img src="../../../images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
