<!--suppress HtmlFormInputWithoutLabel -->
<table class="si_invoice_bot">
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    <tr>
        <th class="si_left" colspan="2">{$LANG.notes}</th>
    </tr>
    <tr>
        <td class='si_invoice_notes' colspan="2">
            <input name="note" id="note" {if isset($defaultInvoice.note)}value="{$defaultInvoice.note|outHtml}"{/if} type="hidden">
            <trix-editor input="note"></trix-editor>
        </td>
    </tr>
    <tr>
        <th>{$LANG.invPref}</th>
        <th>{$LANG.salesRepresentative}</th>
    </tr>
    <tr>
        <td>
            {if !isset($preferences) }
                <em>{$LANG.noPreferences}</em>
            {else}
                <select name="preference_id">
                    {foreach $preferences as $preference}
                        <option {if $preference.pref_id == $defaults.preference}selected {/if}value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">
                            {$preference.pref_description|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </td>
        <td>
            <input id="sales_representative}" name="sales_representative" size="30"
                   value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}" />
        </td>
    </tr>
</table>
