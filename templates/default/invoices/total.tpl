<!--suppress HtmlFormInputWithoutLabel -->
<div class="form__container delay__display">
    {include file="$path/invoiceTypeButtons.tpl" }
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
        {include file="$path/invoiceBillerCustFields.tpl" }
        <div class="row">
            <div class="col__20">
                <label for="description">{$LANG.descriptionUc}:</label>
            </div>
            <div class="col__80">
                <textarea name="description" id="description" rows="3"
                          cols="100%">{if !empty($defaultInvoice.note)}{$defaultInvoice.note|outHtml}{/if}</textarea>
            </div>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$globalInfo.locale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$globalInfo.currency_code}">
        <input type="hidden" name="precision" id="precisionId" value="{$globalInfo.precision}">
        <input type="hidden" name="quantity0" id="quantity0" value="1">
        <div class="row">
            <div class="col__20">
                <label for="unit_price0">{$LANG.grossTotal}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="unit_price" id="unit_price0" size="10" required class="validateNumber"
                       value="{if isset($defaultInvoiceItems[0].unit_price)}{$defaultInvoiceItems[0].unit_price|utilNumber}{/if}"/>
            </div>
        </div>
        {if $defaults.tax_per_line_item > 0}
            {section name=tax loop=$defaults.tax_per_line_item}
                {$index = $smarty.section.tax.index}
                {$taxNumber = $index + 1}
                <div class="row">
                    <div class="col__20">
                        <label for="tax_id[{$index|htmlSafe}]">{$LANG.tax} {$taxNumber|htmlSafe}:</label>
                    </div>
                    <div class="col__80">
                        <select id="tax_id[{$index|htmlSafe}]" name="tax_id[{$index|htmlSafe}]">
                            <option value=""></option>
                            {foreach $taxes as $tax}
                                <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}"
                                        {if isset($defaultInvoiceItems[$line].tax[$index]) &&
                                        $tax.tax_id == $defaultInvoiceItems[$line].tax[$index]}selected{/if}>{$tax.tax_description|htmlSafe}</option>
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
            <div class="col__80 {if !isset($preferences)}pad__top-1-5{/if}">
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
        <div class="row">
            <div class="col__20">
                <label for="sales_representative">{$LANG.salesRepresentative}:</label>
            </div>
            <div class="col__80">
                <input type="text" id="sales_representative" name="sales_representative" size="30"
                       value="{if isset($defaultInvoice.sales_representative)}{$defaultInvoice.sales_representative|htmlSafe}{/if}"/>
            </div>
        </div>
        <br/>
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=invoices&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <div class="si__help-div">
            <img class="tooltip" title="{$LANG.helpInvoiceCustomFields}" src="{$helpImagePath}help-small.png"
                 alt="{$LANG.wantMoreFields}"/>{$LANG.wantMoreFields}
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
