{if !empty($smarty.post.value) && isset($smarty.post.submit) }
    {include file="templates/default/product_value/save.tpl"}
{else}
    {* if  name was inserted *}
    {if isset($smarty.post.submit)}
        <div class="validation_alert"><img src="images/common/important.png" alt=""/>
            You must enter a value
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=product_value&amp;view=add">
        <div class="si_center"><h2>{$LANG.add_product_value}</h2></div>
        <table class="center">
            <tr>
                <th class="left">{$LANG.attribute}:</th>
                <td>
                    <select name="attribute_id">
                        {foreach $product_attributes as $product_attribute}
                            <option value="{$product_attribute.id}">{$product_attribute.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.value}:</th>
                <td>
                    <input type="text" name="value" class="validate[required]"
                           {if isset($smarty.post.value)}value="{$smarty.post.value}"{/if} size="25"/>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}:</th>
                <td>
                    {html_options class=edit name=enabled options=$enabled selected=1}
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=product_value&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt="{$LANG.cancel}"/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="add"/>
    </form>
{/if}
