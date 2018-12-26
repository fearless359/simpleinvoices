{* if  name was inserted *}
{if isset($smarty.post.submit)}
    <div class="validation_alert"><img src="images/common/important.png" alt=""/>
        You must enter a name for the product attribute
    </div>
    <hr/>
{/if}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute&amp;view=add">
    <h3>{$LANG.add_product_attribute}</h3>
    <hr/>
    <table class="center">
        <tr>
            <th class="left">{$LANG.name}</th>
            <td><input type="text" name="name" class="validate[required]" size="50"
                       value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}" /></td>
        </tr>
        <tr>
            <th class="left">{$LANG.type}</th>
            <td>
                <select name="type_id">
                    {foreach $types as $k => $v}
                        <option value="{if isset($v.id)}{$v.id}{/if}">{$LANG[$v.name]}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th class="left">{$LANG.enabled}</th>
            <td>
                {html_options class=edit name=enabled options=$enabled selected=1}
            </td>
        </tr>
        <tr>
            <th class="left">{$LANG.visible}</th>
            <td>
                {html_options class=edit name=visible options=$enabled selected=1}
            </td>
        </tr>
    </table>
    <hr/>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.insert_product_attribute}">
            <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
            {$LANG.save}
        </button>
        <a href="index.php?module=product_attribute&amp;view=manage" class="negative">
            <img src="images/common/cross.png" alt="{$LANG.cancel}" />
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="add"/>
</form>
