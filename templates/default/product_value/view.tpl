<div class="si_center"><h2>{$LANG.productValue}</h2></div>
<table class="center">
    <tr>
        <th class="details_screen left">{$LANG.attribute}:</th>
        <td>{$product_value.name|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.value}:</th>
        <td>{$product_value.value|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.enabled}:</th>
        <td>{$product_value.enabled_text}</td>
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=product_value&amp;view=edit&amp;id={$product_value.id|htmlSafe}">
        <img src="../../../images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
</div>
