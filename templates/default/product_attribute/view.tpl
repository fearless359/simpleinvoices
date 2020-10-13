<div class="si_center"><h2>{$LANG.productAttribute}</h2></div>
<table class="center">
    <tr>
        <th class="details_screen">{$LANG.nameUc}:</th>
        <td class="si_input">{$product_attribute.name}
    </tr>
    <tr>
        <th class="details_screen">{$LANG.type}:</th>
        <td class="si_input">{$product_attribute.type_name|capitalize|htmlSafe}
    </tr>
    <tr>
        <th class="details_screen">{$LANG.enabled}:</th>
        <td class="si_input">{$product_attribute.enabled_text|htmlSafe}
    </tr>
    <tr>
        <th class="details_screen">{$LANG.visible}:</th>
        <td class="si_input">{$product_attribute.visible_text|htmlSafe}
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=product_attribute&amp;view=edit&amp;id={$product_attribute.id|htmlSafe}">
        <img src="../../../images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=product_attribute&amp;view=manage"
       class="negative"> <img src="../../../images/cross.png" alt="{$LANG.cancel}"/>
        {$LANG.cancel}
    </a>
</div>
