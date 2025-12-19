{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="row">
    <div class="col__100">
        <label for="note">{$LANG.notes}:</label>
    </div>
</div>
<div class="row">
    <div class='col__100'>
        <input name="note" id="note" {if isset($defaultInvoice.note)}value="{$defaultInvoice.note|outHtml}"{/if}
               type="hidden">
        <trix-editor input="note"></trix-editor>
    </div>
</div>
<div class="row">
    <div class="col__20">
        <label for="preferenceId">{$LANG.invPref}:</label>
    </div>
    <div class="col__80 {if !isset($preferences)}pad__top-1-5{/if}">
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
</div>
<div class="row">
    <div class="col__20">
        <label for="salesRepresentative">{$LANG.salesRepresentative}:</label>
    </div>
    <div class="col__80">
        <input type="text" id="salesRepresentative" name="sales_representative" size="30"
               value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
    </div>
</div>
