{if !empty($smarty.post.name)}
    {include file="templates/default/product_attribute/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=product_attribute&amp;view=create">
        <h3>{$LANG.addProductAttribute}</h3>
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
                    {html_options class="edit si_input" name=enabled options=$enabled selected=1}
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.visible}</th>
                <td>
                    {html_options class="edit si_input" name=visible options=$enabled selected=1}
                </td>
            </tr>
        </table>
        <hr/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.insertProductAttribute}">
                <img class="button_img" src="../../../images/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=product_attribute&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
