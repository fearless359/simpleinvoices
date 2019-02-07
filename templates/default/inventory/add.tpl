{* If one required field is present then all are. So only need to test the one. *}
{if !empty($smarty.post.product_id)}
    {include file="templates/default/cron/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=inventory&amp;view=add">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.product}</th>
                    <td>
                        <select name="product_id" class="validate[required] product_inventory_change">
                            <option value=''></option>
                            {foreach $product_all as $product}
                                <option value="{$product.id|htmlsafe}"
                                        {if isset($smarty.post.product_id) && $smarty.post.product_id == $product.id}selected{/if}>{$product.description|htmlsafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.quantity}</th>
                    <td>
                        <input class="validate[required]" name="quantity" size="10"
                               {if !empty($smarty.post.quantity)}value="{$smarty.post.quantity|siLocal_number_trim:0}"{/if}>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.date_upper}</th>
                    <td>
                        <input type="text" size="10" name="date" id="date"
                               class="validate[required,custom[date],length[0,10]] date-picker"
                               value="{if !empty($smarty.post.date)}{$smarty.post.date}{else}{'now'|date_format:'%Y-%m-%d'}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.cost}</th>
                    <td>
                        <input class="validate[required]" name="cost" id="cost" size="10"
                               {if !empty($smarty.post.cost)}value="{$smarty.post.cost}"{/if}>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.notes}</th>
                    <td>
                        <!--
                        <textarea name="note" class="editor">{*if isset($smarty.post.note)*}{*$smarty.post.note|outhtml*}{*/if*}</textarea>
                        -->
                        <input name="note" id="note" {if isset($smarty.post.note)}value="{$smarty.post.note|outhtml}"{/if} type="hidden">
                        <trix-editor input="note"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=inventory&amp;view=manage" class="negative">
                    <img src="images/common/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="add"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
