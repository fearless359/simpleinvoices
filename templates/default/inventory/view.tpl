<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.productUc}:</th>
            <td>{$inventory.description|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.dateUc}:</th>
            <td>{$inventory.date|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.quantity}:</th>
            <td>{$inventory.quantity|utilNumberTrim}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.costUc}:</th>
            <td>{$inventory.cost|utilNumber}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.notes}:</th>
            <td>{$inventory.note}</td>
        </tr>
    </table>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=inventory&amp;view=edit&amp;id={$inventory.id|htmlSafe}" class="positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=inventory&amp;view=manage" class="negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>
        {$LANG.cancel}
    </a>
</div>
