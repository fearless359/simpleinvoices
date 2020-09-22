{* If one required field is present then all are. So only need to test the one. *}
{if !empty($smarty.post.product_id)}
    {include file="templates/default/inventory/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=inventory&amp;view=create">
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.productUc}: </th>
                    <td>
                        <select name="product_id" class="si_input validate[required] product_inventory_change" tabindex="10">
                            <option value=''></option>
                            {foreach $product_all as $product}
                                <option value="{$product.id|htmlSafe}"
                                        {if isset($smarty.post.product_id) && $smarty.post.product_id == $product.id}selected{/if}>{$product.description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.quantity}: </th>
                    <td>
                        <input class="si_input validate[required]" name="quantity" size="10" tabindex="20"
                               {if !empty($smarty.post.quantity)}value="{$smarty.post.quantity|utilNumberTrim:0}"{/if}>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.dateUc}: </th>
                    <td>
                        <input type="text" name="date" id="date" class="si_input validate[required,custom[date],length[0,10]] date-picker" size="10" tabindex="30"
                               value="{if !empty($smarty.post.date)}{$smarty.post.date}{else}{'now'|date_format:'%Y-%m-%d'}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.costUc}: </th>
                    <td>
                        <input class="si_input validate[required]" name="cost" id="cost" size="10" tabindex="40"
                               {if !empty($smarty.post.cost)}value="{$smarty.post.cost}"{/if}>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.notes}: </th>
                    <td>
                        <input name="note" id="note" {if isset($smarty.post.note)}value="{$smarty.post.note|outHtml}"{/if} type="hidden">
                        <trix-editor input="note" class="si_input" tabindex="50"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="60">
                    <img class="button_img" src="../../../images/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=inventory&amp;view=manage" class="negative" tabindex="70">
                    <img src="../../../images/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
