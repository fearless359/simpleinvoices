<!--suppress HtmlFormInputWithoutLabel -->
<div class="delay__display">
    {include file="$path/invoiceTypeButtons.tpl" }
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
        <div class="flex__area">
            {include file="$path/invoiceBillerCustFields.tpl" }
            <div class="flex__container flex__start">
                <label for="description">{$LANG.descriptionUc}:</label>
                <div>
                    <textarea name="description" id="description" class="cols__1-span-10"
                              rows="3" cols="100%">{if !empty($defaultInvoice.note)}{$defaultInvoice.note|outHtml}{/if}</textarea>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$globalInfo.locale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$globalInfo.currency_code}">
            <input type="hidden" name="precision" id="precisionId" value="{$globalInfo.precision}">
            <input type="hidden" name="quantity0" id="quantity0" value="1">
            <div class="flex__container flex__start">
                <label for="unit_price0" class="margin__right-1">{$LANG.grossTotal}:</label>
                <input type="text" name="unit_price" id="unit_price0" size="10" required class="validateNumber"
                       value="{if isset($defaultInvoiceItems[0].unit_price)}{$defaultInvoiceItems[0].unit_price|utilNumber}{/if}"/>
            </div>
            {if $defaults.tax_per_line_item > 0}
                <div class="flex__container flex__start">
                    <div class="bold margin__right-1">{$LANG.tax}:</div>
                    {section name=tax loop=$defaults.tax_per_line_item}
                        {$taxNumber = $smarty.section.tax.index}
                        <div class="margin__right-1">
                            <select name="tax_id[{$taxNumber|htmlSafe}]" id="tax_id[{$taxNumber|htmlSafe}]">
                                <option value=""></option>
                                {foreach $taxes as $tax}
                                    <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}"
                                            {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                            $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}>{$tax.tax_description|htmlSafe}</option>
                                {/foreach}
                            </select>
                        </div>
                    {/section}
                </div>
            {/if}
            {$customFields.1}
            {$customFields.2}
            {$customFields.3}
            {$customFields.4}
            <div class="flex__container flex__start">
                <div class="bold margin__right-1">{$LANG.invPref}:</div>
                <div>
                    {if !isset($preferences) }
                        <em>{$LANG.noPreferences}</em>
                    {else}
                        <select name="preference_id" id="preferenceId" class="invoicePreference">
                            {foreach $preferences as $preference}
                                <option {if $preference.pref_id == $defaults.preference}selected{/if}
                                        data-locale="{$preference.locale}" data-currency-code="{$preference.currency_code}"
                                        value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
            </div>
            <div class="flex__container flex__start">
                <div class="bold margin__right-1">{$LANG.salesRepresentative}:</div>
                <input id="sales_representative}" name="sales_representative" size="30"
                       value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
            </div>
            <div class="align__text-center">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=invoices&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <div class="si__help-div">
                <img class="tooltip" title="{$LANG.helpInvoiceCustomFields}" src="{$helpImagePath}help-small.png" alt="{$LANG.wantMoreFields}"/>{$LANG.wantMoreFields}
            </div>
        </div>
        <input type="hidden" id="max_items" name="max_items" value="1"/>
        <input type="hidden" id="typeId" name="type" value="1"/>
        <input type="hidden" name="op" value="create"/>
    </form>
    <script>
        // This causes the tabs to appear after being rendered
        {literal}
        $(document).ready(function () {
            $("div.delay__display").removeClass("delay__display");
        });
        {/literal}
    </script>
</div>
