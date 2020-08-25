<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=inventory&amp;view=save&amp;id={$inventory.id|urlencode}">
    {if $smarty.get.action == 'view'}
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th>{$LANG.product_uc}</th>
                    <td>{$inventory.description|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.date}</th>
                    <td>{$inventory.date|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.quantity}</th>
                    <td>{$inventory.quantity|siLocal_number_trim}</td>
                </tr>
                <tr>
                    <th>{$LANG.cost_uc}</th>
                    <td>{$inventory.cost|siLocal_number}</td>
                </tr>
                <tr>
                    <th>{$LANG.notes}</th>
                    <td>{$inventory.note}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=inventory&amp;view=details&amp;id={$inventory.id|htmlsafe}&amp;action=edit" class="positive">
                <img src="../../../images/report_edit.png" alt="{$LANG.edit}"/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=inventory&amp;view=manage"
               class="negative"> <img src="../../../images/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
            </a>
        </div>
    {elseif $smarty.get.action == 'edit'}
        <div class="si_form">
            <table>
                <tr>
                    <td class="details_screen">{$LANG.date}</td>
                    <td>
                        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                               size="10" name="date" id="date"
                               value="{if isset($inventory.date)}{$inventory.date|htmlsafe}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.product_uc}</td>
                    <td>
                        <select name="product_id" class="validate[required] product_inventory_change">
                            <option value=''></option>
                            {foreach $product_all $product}
                                <option value="{if isset($product.id)}{$product.id|htmlsafe}{/if}"
                                        {if $product.id == $inventory.product_id}selected{/if} >{$product.description|htmlsafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.quantity}</td>
                    <td>
                        <input name="quantity" size="10" class="validate[required]"
                               value='{$inventory.quantity|siLocal_number_trim}'>
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.cost_uc}</td>
                    <td>
                        <input id="cost" name="cost" size="10" class="validate[required]"
                               value='{$inventory.cost|siLocal_number}'>
                    </td>
                </tr>
                <tr>
                    <td class="details_screen">{$LANG.notes}</td>
                    <td>
                        <!--
                        <textarea name="note" class="editor">{*$inventory.note|outhtml*}</textarea>
                        -->
                        <input name="note" id="note" {if isset($inventory.note)}value="{$inventory.note|outhtml}"{/if} type="hidden">
                        <trix-editor input="note"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=inventory&amp;view=manage" class="negative">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit"/>
    {/if}
</form>
