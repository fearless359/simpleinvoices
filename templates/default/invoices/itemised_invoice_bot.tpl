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
            <textarea class="editor" name="note" rows="5" cols="100%">{$defaultInvoice.note}</textarea>
        </td>
    </tr>
    <tr>
        <th>{$LANG.inv_pref}</th>
        <th>{$LANG.sales_representative}</th>
    </tr>
    <tr>
        <td>
            {if $preferences == null }
                <em>{$LANG.no_preferences}</em>
            {else}
                <select name="preference_id">
                    {foreach from=$preferences item=preference}
                        <option {if $preference.pref_id == $defaults.preference}selected {/if}value="{$preference.pref_id|htmlsafe}">
                            {$preference.pref_description|htmlsafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </td>
        <td>
            <input id="sales_representative}" name="sales_representative" size="30"
                   value="{$defaultInvoice.sales_representative|htmlsafe}" />
        </td>
    </tr>
</table>
