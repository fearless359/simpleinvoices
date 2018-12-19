<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" method="post"
      action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}" >
    {if $smarty.get.action== 'view' }
        <div class="si_center"><h2>{$LANG.product_attribute}</h2></div>
        <table class="center">
            <tr>
                <th class="left">{$LANG.id}: </th>
                <td>{$product_attribute.id}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.name}: </th>
                <td>{$product_attribute.name}
            </tr>
            <tr>
                <th class="left">{$LANG.type}: </th>
                <td>{$product_attribute.type_name|capitalize|htmlsafe}
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}: </th>
                <td>{$product_attribute.enabled_text|htmlsafe}
            </tr>
            <tr>
                <th class="left">{$LANG.visible}: </th>
                <td>{$product_attribute.visible_text|htmlsafe}
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute.id|htmlsafe}&amp;action=edit">
                <img src="images/famfam/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=product_attribute&amp;view=manage"
               class="negative"> <img src="images/common/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
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
                <td><input type="text" name="name" class="validate[required]" size="50"
                           value="{if isset($product_attribute.name)}{$product_attribute.name}{/if}" /></td>
            </tr>
            <tr>
                <th>{$LANG.type}</th>
                <td>
                    <select name="type_id">
                        {foreach $types as $k => $v}
                            <option value="{if isset($v.id)}{$v.id}{/if}" {if $product_attribute.type_id == $v.id} selected {/if}>{$LANG[$v.name]}</option>
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
        <input type="hidden" name="op" value="edit"/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=product_attribute&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
        </div>
    {/if}
</form>
