<input type="hidden" name="quantity0" value="1"/>
<input type="hidden" name="id0" value="{$invoiceItems[0].id|htmlSafe}"/>
<input type="hidden" name="products0" value="{$invoiceItems[0].product_id|htmlSafe}"/>
<div class="grid__container grid__head-10">
    <label for="description0" class="cols__1-span-10">{$LANG.descriptionUc}:</label>
    <div class="cols__1-span-10">
        <textarea name="description0" id="description0" style="overflow:scroll;" rows="3" cols="100%"
                  data-row-num="0" data-description="{$LANG.descriptionUc}">{$invoiceItems[0].description|outHtml}</textarea>
    </div>
</div>
<div class="grid__container grid__head-10">
    <label for="unitPrice0Id" class="cols__1-span-2">{$LANG.grossTotal}:</label>
    <div class="cols__3-span-3">
        <input type="text" class="align__text-right" required name="unit_price0" id="unitPrice0Id"
               value="{$invoiceItems[0].unit_price|utilNumber}" size="10"/>
    </div>
    {if $defaults.tax_per_line_item > 0}
        <div class="cols__6-span-1 bold align__text-right">{$LANG.tax}:&nbsp;</div>
        {section name=tax loop=$defaults.tax_per_line_item}
            {$index = $smarty.section.tax.index}
            {$taxNumber = $invoiceItems[0].tax.$index}
            {$colStart = $smarty.section.tax.index + 7}
            <div class="cols__{$colStart}-span-1 margin__right-1">
                <!--suppress HtmlFormInputWithoutLabel -->
                <select id="tax_id[0][{$smarty.section.tax.index|htmlSafe}]"
                        name="tax_id[0][{$smarty.section.tax.index|htmlSafe}]">
                    <option value=""></option>
                    {assign var="index" value=$smarty.section.tax.index}
                    {foreach $taxes as $tax}
                        <option {if isset($taxNumber) && $tax.tax_id == $taxNumber}selected{/if}
                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        {/section}
    {/if}
</div>
<br/>
{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="grid__container grid__head-10">
    <label for="preferenceId" class="cols__1-span-2">{$LANG.invPref}:</label>
    <div class="cols__3-span-3">
        {if !isset($preferences) }
            <em>{$LANG.noPreferences}</em>
        {else}
            <select name="preference_id" id="preferenceId">
                {foreach $preferences as $preference}
                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                {/foreach}
            </select>
        {/if}
    </div>
    <label for="salesRepresentativeId" class="cols__6-span-2">{$LANG.salesRepresentative}:</label>
    <div class="cols__8-span-3">
        <input name="sales_representative" id="salesRepresentativeId" size="30"
               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
    </div>
</div>
