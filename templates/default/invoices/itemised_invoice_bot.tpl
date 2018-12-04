<table class="si_invoice_bot">
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    <tr>
        <th class="left" colspan="2">{$LANG.notes}</th>
    </tr>
    <tr>
        <td class='si_invoice_notes' colspan="2">
            <textarea class="editor" name="note" rows="5" cols="100%">{if isset($defaultInvoice.note)}{$defaultInvoice.note}{/if}</textarea>
        </td>
    </tr>
    <tr>
        <th>{$LANG.inv_pref}</th>
        <th>{$LANG.sales_representative}</th>
    </tr>
    <tr>
        <td>
            {if !isset($preferences) }
                <em>{$LANG.no_preferences}</em>
            {else}
                <select name="preference_id">
                    {foreach from=$preferences item=preference}
                        <option {if $preference.pref_id == $defaults.preference}selected {/if}value="{if isset($preference.pref_id)}{$preference.pref_id|htmlsafe}{/if}">
                            {$preference.pref_description|htmlsafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </td>
        <td>
            <input id="sales_representative}" name="sales_representative" size="30"
                   value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlsafe}{/if}" />
        </td>
    </tr>
</table>
