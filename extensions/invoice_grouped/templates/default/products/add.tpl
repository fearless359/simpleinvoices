
{* if bill is updated or saved.*}

{if !empty($smarty.post.description) && isset($smarty.post.submit) }
    {include file="templates/default/products/save.tpl"}
{else}
    {* if  name was inserted *}
    {if isset($smarty.post.submit)}
        <!--suppress HtmlFormInputWithoutLabel -->
        <div class="validation_alert">
            <img src="images/important.png"/>
            You must enter a description for the product
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=products&amp;view=add">
        <table class="center">
            <tr>
                <th>{$LANG.description_uc}
                    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.required_field}">
                        <img src="{$helpImagePath}required-small.png"/></a>
                </th>
                <td><input type="text" name="description" value="{if isset($smarty.post.description)}{$smarty.post.description}{/if}" size="50" id="description" class="required edit" onblur="checkField(this);"/></td>
            </tr>
            <tr>
                <th>{$LANG.unit_price}</th>
                <td><input type="text" class="edit" name="unit_price" value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price}{/if}" size="25"/></td>
            </tr>
            <tr>
                <th>{$LANG.default_tax}</th>
                <td>
                    <select name="default_tax_id">
                        <option value=''></option>
                        {foreach from=$taxes item=tax}
                            <option value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}">{$tax.tax_description}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {if !empty($customFieldLabel.product_cf1)}
                <tr>
                    <th>{$customFieldLabel.product_cf1}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td>
                        <select name="custom_field1">
                            <option value=""></option>
                            {foreach from=$product_group item=pg}
                                <option value="{if isset($pg.name)}{$pg.name}{/if}">{$pg.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf2)}
                <tr>
                    <th>{$customFieldLabel.product_cf2}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td><input type="text" class="edit" name="custom_field2" value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2}{/if}" size="50"/></td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf3)}
                <tr>
                    <th>{$customFieldLabel.product_cf3}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td><input type="text" class="edit" name="custom_field3" value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3}{/if}" size="50"/></td>
                </tr>
            {/if}
            {if !empty($customFieldLabel.product_cf4)}
                <tr>
                    <th>{$customFieldLabel.product_cf4}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                            <img src="{$helpImagePath}help-small.png" alt=""/></a>
                    </th>
                    <td><input type="text" class="edit" name="custom_field4" value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4}{/if}" size="50"/></td>
                </tr>
            {/if}
            <tr>
                <th>{$LANG.notes}</th>
                <td>
                    <!--
                    <textarea class="editor" name="notes"/>{*if isset($smarty.post.notes)*}{*$smarty.post.notes|unescape*}{*/if*}</textarea>
                    -->
                    <input name="notes" id="notes" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outhtml}"{/if} type="hidden">
                    <trix-editor input="notes"></trix-editor>
                </td>
            </tr>
            <tr>
                <th>{$LANG.enabled}</th>
                <td>
                    {html_options class=edit name=enabled options=$enabled selected=1}
                </td>
            </tr>
        </table>
        <br/>
        <table class="center">
            <tr>
                <td>
                    <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                        <img class="button_img" src="../../../../../images/tick.png" alt=""/>
                        {$LANG.save}
                    </button>

                    <input type="hidden" name="op" value="insert_product"/>

                    <a href="index.php?module=products&amp;view=manage" class="negative">
                        <img src="../../../../../images/cross.png" alt=""/>
                        {$LANG.cancel}
                    </a>
                </td>
            </tr>
        </table>
    </form>
{/if}
