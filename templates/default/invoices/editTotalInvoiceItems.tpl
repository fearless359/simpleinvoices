<div class="row">
    <div class="col__20">
        <label for="description0">{$LANG.descriptionUc}:</label>
    </div>
    <div class="col__80">
        <textarea name="description0" id="description0" style="width:100%;" rows="3" cols="100%" data-row-num="0"
                  data-description="{$LANG.descriptionUc}">{$invoiceItems[0].description|outHtml}</textarea>
    </div>
</div>
<input type="hidden" name="locale" id="localeId" value="{$invoice.locale}">
<input type="hidden" name="currency_code" id="currencyCodeId" value="{$invoice.currency_code}">
<input type="hidden" name="precision" id="precisionId" value="{$invoice.precision}">
<input type="hidden" name="quantity0" id="quantity0" size="10" value="1">
<input type="hidden" name="line_item0" id="line_item0" value="{$invoiceItems[0].id|htmlSafe}">
<input type="hidden" name="id0" id="id0" value="{$invoiceItems[0].id|htmlSafe}"/>
<input type="hidden" name="products0" id="products0" value="{$invoiceItems[0].product_id|htmlSafe}"/>
<div class="row">
    <div class="col__20">
        <label for="unit_price0">{$LANG.grossTotal}:</label>
    </div>
    <div class="col__80">
        <input type="text" name="unit_price0" id="unit_price0" required class="validateNumber"
               value="{$invoiceItems[0].unit_price|utilNumber:$invoice.precision:$invoice.locale}" size="10"/>
    </div>
</div>
{if $defaults.tax_per_line_item > 0}
    {section name=tax loop=$defaults.tax_per_line_item}
        {$index = $smarty.section.tax.index}
        {$taxNumber = $invoiceItems[0].tax.$index}
        <div class="row">
            <div class="col__20">
                <label for="tax_id[0][{$index|htmlSafe}]">{$LANG.tax}&nbsp;{$taxNumber|htmlSafe}:</label>
            </div>
            <div class="col__80">
                <!--suppress HtmlFormInputWithoutLabel -->
                <select id="tax_id[0][{$index|htmlSafe}]" name="tax_id[0][{$index|htmlSafe}]">
                    <option value=""></option>
                    {assign var="index" value=$index}
                    {foreach $taxes as $tax}
                        <option {if isset($taxNumber) && $tax.tax_id == $taxNumber}selected{/if}
                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    {/section}
{/if}
{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="row">
    <div class="col__20">
        <label for="preferenceId">{$LANG.invPref}:</label>
    </div>
    <div class="col__80">
        {if !isset($preferences) }
            <em>{$LANG.noPreferences}</em>
        {else}
            <select name="preference_id" id="preferenceId" class="invoicePreference">
                {foreach $preferences as $preference}
                    <option {if $preference.pref_id == $invoice.preference_id}selected{/if}
                            data-locale="{$preference.locale}" data-currency-code="{$preference.currency_code}"
                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                {/foreach}
            </select>
        {/if}
    </div>
</div>
<div class="row">
    <div class="col__20">
        <label for="salesRepresentativeId">{$LANG.salesRep}:</label>
    </div>
    <div class="col__80">
        <input type="text" name="sales_representative" id="salesRepresentativeId" size="30"
               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
    </div>
</div>
