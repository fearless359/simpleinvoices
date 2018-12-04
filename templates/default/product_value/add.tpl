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
    <form name="frmpost" action="index.php?module=product_value&amp;view=add" method="post">
        <input type="hidden" name="op" value="insert_product_value"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
        <div class="si_center"><h2>{$LANG.add_product_value}</h2></div>
        <table class="center">
            <tr>
                <th class="left">{$LANG.attribute}: </th>
                <td>
                    <select name="attribute_id">
                        {foreach from=$product_attributes item=product_attribute}
                            <option value="{if isset($product_attribute.id)}{$product_attribute.id}{/if}">{$product_attribute.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.value}: </th>
                <td><input type="text" name="value" value="{if isset($smarty.post.value)}{$smarty.post.value}{/if}" size="25"/></td>
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}: </th>
                <td>
                    {html_options class=edit name=enabled options=$enabled selected=1}
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=product_value&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </form>
{/if}
