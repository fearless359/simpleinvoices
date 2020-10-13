    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.nameUc}:</th>
                <td>{$productGroup.name|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.markupUc}%:</th>
                <td>{$productGroup.markup}%</td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <a href="index.php?module=product_groups&amp;view=edit&amp;name={$productGroup.name|htmlSafe}" class="positive">
            <img src="../../../images/report_edit.png" alt=""/>
            {$LANG.edit}
        </a>
    </div>
