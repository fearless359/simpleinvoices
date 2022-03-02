<div class="grid__container grid__head-10">
    <div class="cols__1-span-1 bold align__text-right">
        <label for="quantity0 align__text-right margin__right-1">{$LANG.quantityShort}
            <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpQuantity}" src="{$helpImagePath}required-small.png" alt=""/>
        </label>
    </div>
    <div class="cols__2-span-4 bold align__text-center">{$LANG.item}</div>
    {$begCol = 6}
    {section name=taxHeader loop=$defaults.tax_per_line_item }
        <div class="cols__{$begCol}-span-1 bold align__text-center">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.taxHeader.iteration|htmlSafe}{/if}</div>
        {$begCol = $begCol + 1}
    {/section}
    <div class="cols__{$begCol}-span-1 bold align__text-center">{$LANG.unitPrice}</div>
</div>
<div id="itemtable" data-number-tax-items="{$defaults.tax_per_line_item}">
    {section name=line loop=$dynamic_line_items}
        {$line = $smarty.section.line.index}
        <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
        <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"/>
        {* id of invoice_items record *}
        <div class="lineItem" id="row{$line|htmlSafe}">
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-1">
                    {if $line == "0"}
                        {$cols = "16% 84%"} {* Account for image not displaying on first line. *}
                    {else}
                        {$cols = "9% 7% 84%"}
                    {/if}
                    <div id="qtyColumn" style="display:grid;grid-template-columns: {$cols};">
                        <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" title="{$LANG.deleteLineItem}"
                           {if $line == 0}style="display:none;"{/if}
                           data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}>
                            <img id="delete_image{$line|htmlSafe}" class="margin__top-0-5"
                                 src="images/delete_item.png" alt="{$LANG.deleteLineItem}"/>
                        </a>
                        <span>&nbsp;</span>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" {if $line == 0}required{/if}
                               class="align__text-right {if $line == 0}validate-quantity{/if}" data-row-num="{$line|htmlSafe}"
                               value="{if isset($defaultInvoiceItems[$line].quantity)}{$defaultInvoiceItems[$line].quantity|utilNumberTrim}{/if}">
                    </div>
                </div>
                <div class="cols__2-span-4">
                    {if !isset($products) }
                        <em>{$LANG.noProducts}</em>
                    {else}
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select name="products{$line|htmlSafe}" id="products{$line|htmlSafe}"
                                class="product_change width_100 margin__left-0-5" {if $line == 0}required{/if}
                                data-row-num="{$line|htmlSafe}" data-description="{$LANG.descriptionUc}"
                                data-product-groups-enabled="{$defaults.product_groups}">
                            <option value=""></option>
                            {foreach $products as $product}
                                <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                                        {if isset($defaultInvoiceItems[$line].product_id) &&
                                        $product.id == $defaultInvoiceItems[$line].product_id}selected{/if}>{$product.description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                {$begCol = 6}
                {section name=tax loop=$defaults.tax_per_line_item}
                    {$taxNumber = $smarty.section.tax.index}
                    <div class="cols__{$begCol}-span-1">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                data-row-num="{$line|htmlSafe}" class="margin__left-1">
                            <option value=""></option>
                            {foreach $taxes as $tax}
                                <option {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}
                                        value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </div>
                    {$begCol = $begCol + 1}
                {/section}
                <div class="cols__{$begCol}-span-1">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <input class="align__text-right margin__left-1" id="unit_price{$line|htmlSafe}" name="unit_price{$line|htmlSafe}" size="9"
                           {if $line == "0"}required{/if} data-row-num="{$line|htmlSafe}"
                           value="{if isset($defaultInvoiceItems[$line].unit_price)}{$defaultInvoiceItems[$line].unit_price|utilNumber}{/if}"/>
                </div>
            </div>
            <div class="grid__container grid__head-10 details" {if $defaults.invoice_description_open != $smarty.const.ENABLED}style="display:none;"{/if}>
                <div class="cols__2-span-9">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <textarea name="description{$line|htmlSafe}" id="description{$line|htmlSafe}" rows="3" cols="99"
                              class="margin__left-0-5" data-row-num="{$line|htmlSafe}"
                              data-description="{$LANG.descriptionUc}">{if isset($defaultInvoiceItems[$line].description)}{$defaultInvoiceItems[$line].description|htmlSafe}{/if}</textarea>
                </div>
            </div>
        </div>
    {/section}
</div>
