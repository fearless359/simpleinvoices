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
        <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"
               value="{if isset($cronInvoiceItems[$line].id)}{$cronInvoiceItems[$line].id|htmlSafe}{/if}"/>
        {* id of invoice_items record *}
        <div class="lineItem" id="row{$line|htmlSafe}">
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-1">
                    <div id="qtyColumn" style="display:grid;grid-template-columns:9% 7% 84%;">
                        <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" title="{$LANG.deleteLineItem}"
                           data-row-num="{$line|htmlSafe}" data-delete-line-item="{$config.confirmDeleteLineItem}"
                           tabindex="-1">
                            <img id="delete_image{$line|htmlSafe}" class="margin__top-0-5"
                                 src="images/delete_item.png" alt="{$LANG.deleteLineItem}"/>
                        </a>
                        <span>&nbsp;</span>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" {if $line == 0}required{/if}
                               class="align__text-right {if $line == 0}validate-quantity{/if}" data-row-num="{$line|htmlSafe}"
                               value="{if isset($cronInvoiceItems[$line].quantity)}{$cronInvoiceItems[$line].quantity|utilNumberTrim}{/if}"
                               tabindex="{$line}10">
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
                                data-product-groups-enabled="{$defaults.product_groups}"
                                tabindex="{$line}20">
                            <option value="0"></option>
                            {foreach $products as $product}
                                <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                                        {if isset($cronInvoiceItems[$line].product_id) &&
                                        $product.id == $cronInvoiceItems[$line].product_id}selected{/if}>{$product.description|htmlSafe}</option>
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
                                data-row-num="{$line|htmlSafe}" class="margin__left-1"
                                tabindex="{$line}3{$taxNumber}">
                            <option value=""></option>
                            {foreach $taxes as $tax}
                                <option {if isset($cronInvoiceItems[$line].tax[$taxNumber]) &&
                                $tax.tax_id == $cronInvoiceItems[$line].tax[$taxNumber]}selected{/if}
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
                           value="{if isset($cronInvoiceItems[$line].unit_price)}{$cronInvoiceItems[$line].unit_price|utilNumber}{/if}"
                           tabindex="{$line}40"/>
                </div>
            </div>
            <div class="grid__container grid__head-10 details" {if $defaults.invoice_description_open != $smarty.const.ENABLED}style="display:none;"{/if}>
                <div class="cols__2-span-9">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <textarea name="description{$line|htmlSafe}" id="description{$line|htmlSafe}" rows="3" cols="99"
                              class="margin__left-0-5" data-row-num="{$line|htmlSafe}"
                              placeholder="{$LANG.descriptionUc}"
                              tabindex="{$line}50">{if isset($cronInvoiceItems[$line].description)}{$cronInvoiceItems[$line].description|htmlSafe}{/if}</textarea>
                </div>
            </div>
        </div>
    {/section}
</div>
