{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="flex__container flex__start">
    <label for="note">{$LANG.notes}:</label>
</div>
<div class="grid__container grid__head-10">
    <div class='cols__1-span-10'>
        <input name="note" id="note" {if isset($defaultInvoice.note)}value="{$defaultInvoice.note|outHtml}"{/if} type="hidden">
        <trix-editor input="note"></trix-editor>
    </div>
</div>
<div class="flex__container flex__start">
    <label for="preferenceId" class="margin__right-1">{$LANG.invPref}:</label>
    {if !isset($preferences) }
        <em>{$LANG.noPreferences}</em>
    {else}
        <select name="preference_id" id="preferenceId" class="invoicePreference"
                data-curr-pref-id="{$defaults.preference}">
            {foreach $preferences as $preference}
                <option {if $preference.pref_id == $defaults.preference}selected{/if}
                        data-locale="{$preference.locale}" data-currency-code="{$preference.currency_code}"
                        value="{$preference.pref_id|htmlSafe}">{$preference.pref_description|htmlSafe}</option>
            {/foreach}
        </select>
    {/if}
</div>
<div class="flex__container flex__start">
    <label for="salesRepresentative" class="margin__right-1">{$LANG.salesRepresentative}:</label>
    <input id="salesRepresentative" name="sales_representative" size="30"
           value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
</div>
