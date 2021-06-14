<table class="center">
    <tr>
        <th class="details_screen left">{$LANG.attribute}:</th>
        <td>{$product_attribute_values.name|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.value}:</th>
        <td>{$product_attribute_values.value|htmlSafe}</td>
    </tr>
    <tr>
        <th class="details_screen left">{$LANG.enabled}:</th>
        <td>{$product_attribute_values.enabled_text}</td>
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=product_attribute_values&amp;view=edit&amp;id={$product_attribute_values.id|htmlSafe}">
        <img src="images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=product_attribute_values&amp;view=manage" class="negative">
        <img src="images/cross.png" alt="{$LANG.cancel}" />
        {$LANG.cancel}
    </a>
</div>
