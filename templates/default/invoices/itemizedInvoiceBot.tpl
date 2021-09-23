{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="grid__container grid__head-10">
    <label for="note" class="cols__1-span-1">{$LANG.notes}:</label>
</div>
<div class="grid__container grid__head-10">
    <div class='cols__1-span-10'>
        <input name="note" id="note" {if isset($defaultInvoice.note)}value="{$defaultInvoice.note|outHtml}"{/if} type="hidden">
        <trix-editor input="note"></trix-editor>
    </div>
</div>
<div class="grid__container grid__head-10">
    <label for="preferenceId" class="cols__1-span-2 align__text-right margin__right-1">{$LANG.invPref}:</label>
    <div class="cols__3-span-3">
        {if !isset($preferences) }
            <em>{$LANG.noPreferences}</em>
        {else}
            <select name="preference_id" id="preferenceId">
                {foreach $preferences as $preference}
                    <option {if $preference.pref_id == $defaults.preference}selected{/if}
                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                {/foreach}
            </select>
        {/if}
    </div>
    <label for="salesRepresentative" class="cols__6-span-2 align__text-right margin__right-1">{$LANG.salesRepresentative}:</label>
    <div class="cols__8-span-3">
        <input id="salesRepresentative" name="sales_representative" size="30"
               value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
    </div>
</div>
