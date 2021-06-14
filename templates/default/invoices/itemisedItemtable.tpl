<div class="grid__container grid__head-10">
    <div class="cols__1-span-1 bold si_right si_margin__right-1">{$LANG.quantity}</div>
    <div class="cols__2-span-5 bold si_center si_margin__right-1">{$LANG.item}</div>
    {section name=taxHeader loop=$defaults.tax_per_line_item }
        <div class="cols__{$smarty.section.taxHeader.index+7}-span-1 bold si_margin__right-1">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.taxHeader.iteration|htmlSafe}{/if}</div>
    {/section}
    <div class="cols__{$defaults.tax_per_line_item+7}-span-1 bold si_center si_margin__right-1">{$LANG.unitPrice}</div>
</div>
{section name=line loop=$dynamic_line_items}
    {$line = $smarty.section.line.index}
    <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
    <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"/> {* id of invoice_items record *}
    <div class="line_item grid__container grid__head-10" id="row{$line|htmlSafe}">
        <div class="cols__1-span-1">
            <div style="display:grid;grid-template-columns: 9% 7% 0 84%;">
                {if $line == "0"}&nbsp;
                {else}
                    <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" title="{$LANG.deleteLineItem}"
                       style="display:{if $line == "0"}none{else}inline{/if};"
                       data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}>
                        <img id="delete_image{$line|htmlSafe}" src="images/delete_item.png" alt="{$LANG.deleteLineItem}"
                             style="margin-top: .5rem;"/>
                    </a>
                {/if}
                <span>&nbsp;</span>
                <label for="quantity{$line|htmlSafe}"></label>
                <input class="si_right{if $line == 0} validate[required,min[.01],custom[number]]{/if}"
                       type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" size="5"
                       data-row-num="{$line|htmlSafe}"
                       value="{if isset($defaultInvoiceItems[$line].quantity)}{$defaultInvoiceItems[$line].quantity|utilNumberTrim}{/if}" >
            </div>
        </div>
        <div class="cols__2-span-5">
            {if !isset($products) }
                <em>{$LANG.noProducts}</em>
            {else}
                <label for="products{$line|htmlSafe}"></label>
                <select name="products{$line|htmlSafe}" id="products{$line|htmlSafe}"
                        class="si_input product_change width_95{if $line == 0} validate[required]{/if}"
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
        {section name=tax loop=$defaults.tax_per_line_item}
            {$taxNumber = $smarty.section.tax.index}
            {$colStart = $smarty.section.tax.index + 7}
            <div class="cols__{$colStart}-span-1 si_margin__right-1">
                <label for="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"></label>
                <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                        name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                        data-row-num="{$line|htmlSafe}" >
                    <option value=""></option>
                    {foreach $taxes as $tax}
                        <option {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                    $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}
                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            </div>
        {/section}
        <div class="cols__{$defaults.tax_per_line_item+7}-span-1">
            <label for="unit_price{$line|htmlSafe}"></label>
            <input class="{if $line == "0"}validate[required]{/if}" id="unit_price{$line|htmlSafe}"
                   name="unit_price{$line|htmlSafe}" size="9" data-row-num="{$line|htmlSafe}"
                   value="{if isset($defaultInvoiceItems[$line].unit_price)}{$defaultInvoiceItems[$line].unit_price|utilNumber}{/if}"/>
        </div>
    </div>
    <div class="grid__container grid__head-10 details {if $defaults.invoice_description_open != $smarty.const.ENABLED}si_hide{/if}">
        <div class="cols__1-span-1">
            <label for="description{$line|htmlSafe}">&nbsp;</label>
        </div>
        <div class="cols__2-span-9">
             <textarea name="description{$line|htmlSafe}" style="overflow:scroll;"
                       id="description{$line|htmlSafe}" rows="3" cols="99"
                       data-row-num="{$line|htmlSafe}" data-description="{$LANG.descriptionUc}"
                       >{if isset($defaultInvoiceItems[$line].description)}{$defaultInvoiceItems[$line].description|htmlSafe}{/if}</textarea>
        </div>
    </div>
{/section}
