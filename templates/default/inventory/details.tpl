<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=inventory&amp;view=save&amp;id={$inventory.id|urlencode}">
    {if $smarty.get.action == 'view'}
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.product_uc}: </th>
                    <td>{$inventory.description|htmlsafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.date_uc}: </th>
                    <td>{$inventory.date|htmlsafe}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.quantity}: </th>
                    <td>{$inventory.quantity|siLocal_number_trim}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.cost_uc}: </th>
                    <td>{$inventory.cost|siLocal_number}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.notes}: </th>
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
                    <th class="details_screen">{$LANG.date_uc}: </th>
                    <td>
                        <input type="text" class="si_input validate[required,custom[date],length[0,10]] date-picker"
                               name="date" id="date" size="10" tabindex="10"
                               value="{if isset($inventory.date)}{$inventory.date|htmlsafe}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.product_uc}: </th>
                    <td>
                        <select name="product_id" class="si_input validate[required] product_inventory_change" tabindex="20">
                            <option value=''></option>
                            {foreach $product_all $product}
                                <option value="{if isset($product.id)}{$product.id|htmlsafe}{/if}"
                                        {if $product.id == $inventory.product_id}selected{/if} >{$product.description|htmlsafe}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.quantity}: </th>
                    <td>
                        <input name="quantity" class="si_input validate[required]" size="10" tabindex="30"
                               value='{$inventory.quantity|siLocal_number_trim}'>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.cost_uc}: </th>
                    <td>
                        <input id="cost" name="cost" class="si_input validate[required]" size="10" tabindex="40"
                               value='{$inventory.cost|siLocal_number}'>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.notes}: </th>
                    <td>
                        <input name="note" id="note" {if isset($inventory.note)}value="{$inventory.note|outhtml}"{/if} type="hidden">
                        <trix-editor input="note" class="si_input" tabindex="50"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=inventory&amp;view=manage" class="negative" tabindex="60">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit"/>
    {/if}
</form>
