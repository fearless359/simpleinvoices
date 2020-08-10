<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_value&amp;view=save&amp;id={$smarty.get.id}">
    {if $smarty.get.action== 'view' }
        <div class="si_center"><h2>{$LANG.product_value}</h2></div>
        <table class="center">
            <tr>
                <th class="left">{$LANG.id}: </th>
                <td>{$product_value.id}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.attribute}: </th>
                <td>{$product_value.attribute|htmlsafe}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.value}: </th>
                <td>{$product_value.value|htmlsafe}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}: </th>
                <td>{$product_value.enabled_text}</td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=product_value&amp;view=details&amp;id={$product_value.id|htmlsafe}&amp;action=edit">
                <img src="../../../images/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <div class="si_center"><h2>{$LANG.product_value}</h2></div>
        <table class="center">
            <tr>
                <th style="text-align:left;">{$LANG.id}:</th>
                <td>{$product_value.id}</td>
            </tr>
            <tr>
                <th style="text-align:left;">{$LANG.attribute}: </th>
                <td>
                    <select name="attribute_id">
                        {foreach $product_attributes as $product_attribute}
                            <option {if $product_attribute.id == $product_value.attribute_id}selected{/if}
                                    value="{$product_attribute.id}">{$product_attribute.name}</option>
                        {/foreach}
                    </select>
                </td>
            <tr>
                <th style="text-align:left;">{$LANG.value}: </th>
                <td><input type="text" name="value" value="{$product_value.value}" size="50"/></td>
            </tr>
            <tr>
                <th style="text-align:left;">{$LANG.status}: </th>
                <td>{html_options name=enabled options=$enabled selected=$product_value.enabled}</td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=product_value&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt="" />
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
    {/if}
</form>
