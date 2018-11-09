<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" method="post"
      action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}" >
    {if $smarty.get.action== 'view' }
        <div class="si_center"><h2>{$LANG.product_attribute}</h2></div>
        <table class="center">
            <tr>
                <th class="left">{$LANG.id}</th>
                <td>{$product_attribute.id}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.name}: </th>
                <td><input type="text" name="name" value="{$product_attribute.name}" size="50" readonly /></td>
            </tr>
            <tr>
                <th class="left">{$LANG.type}: </th>
                <td><input type="text" name="attribute_type" value="{$product_attribute.type|capitalize|htmlsafe}" readonly /></td>
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}: </th>
                <td><input type="text" name="enabled" value="{$product_attribute.wording_for_enabled|htmlsafe}" readonly /></td>
            </tr>
            <tr>
                <th class="left">{$LANG.visible}: </th>
                <td><input type="text" name="visible" value="{$product_attribute.wording_for_visible|htmlsafe}" readonly /></td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute.id|htmlsafe}&amp;action=edit">
                <img src="images/famfam/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <div class="si_center"><h2>{$LANG.product_attribute}</h2></div>
        <table class="center">
            <tr>
                <th class="details_screen">{$LANG.id}</th>
                <td>{$product_attribute.id}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.name}</th>
                <td><input type="text" name="name" value="{$product_attribute.name}" size="50"/></td>
            </tr>
            <tr>
                <th>{$LANG.type}</th>
                <td>
                    <select name="type_id">
                        {foreach from=$types key=k item=v}
                            <option value="{$v.id}" {if $product_attribute.type_id == $v.id} selected {/if}>{$LANG[$v.name]}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th>{$LANG.enabled}</th>
                <td>
                    {html_options name=enabled options=$enabled selected=$product_attribute.enabled}
                </td>
            </tr>
            <tr>
                <th>{$LANG.visible}</th>
                <td>
                    {html_options name=visible options=$enabled selected=$product_attribute.visible}
                </td>
            </tr>
        </table>
        <input type="hidden" name="op" value="edit_product_attribute"/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <button type="submit" class="negative" name="cancel_change" value="{$LANG.cancel}">
                <img class="button_img" src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </button>
        </div>
    {/if}
</form>
