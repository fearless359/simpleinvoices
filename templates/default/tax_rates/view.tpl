<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.descriptionUc}:</th>
            <td>{$tax.tax_description|htmlSafe}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.rateUc}:
                <a class="cluetip" href="#" title="{$LANG.taxRate}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxRateSign">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                {$tax.tax_percentage|utilNumber} {$tax.type|htmlSafe}
            </td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.enabled}:</th>
            <td>{$tax.enabled_text|htmlSafe}</td>
        </tr>
    </table>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=tax_rates&amp;view=edit&amp;id={$tax.tax_id|urlencode}" class="positive">
        <img src="../../../images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
</div>

