<!--suppress HtmlFormInputWithoutLabel -->
<table id="itemtable" class="si_invoice_items">
    <thead>
    <tr>
        <th class=""></th>
        <th class="">{$LANG.quantity}</th>
        <th class="">{$LANG.item}</th>
        {section name=tax_header loop=$defaults.tax_per_line_item }
            <th class="">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlSafe}{/if} </th>
        {/section}
        <th class="">{$LANG.unitPrice}</th>
    </tr>
    </thead>
    {section name=line start=0 loop=$dynamic_line_items step=1}
        {assign var="line" value=$smarty.section.line.index }
        <tbody class="line_item" id="row{$line|htmlSafe}">
        <tr>
            <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
            <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"/> {* id of invoice_items record *}
            <td>
                <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" style="display: {if $line == 0}none{else}inline{/if};"
                   data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}
                   title="{$LANG.deleteRow}">
                    <img id="delete_image{$line|htmlSafe}" src="images/delete_item.png" style="height:16px;width:16px;" alt=""/>
                </a>
            </td>
            <td>
                <input class="si_right{if $line == 0} validate[required,min[.01],custom[number]]{/if}"
                       type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" size="5"
                       data-row-num="{$line|htmlSafe}"
                       value="{if isset($defaultInvoiceItems[$line].quantity)}{$defaultInvoiceItems[$line].quantity|utilNumberTrim}{/if}" >
            </td>
            <td>
                {if !isset($products) }
                    <em>{$LANG.noProducts}</em>
                {else}
                    <select id="products{$line|htmlSafe}" name="products{$line|htmlSafe}"
                            class="si_input product_change{if $line == 0} validate[required]{/if}"
                            data-row-num="{$line|htmlSafe}" data-description="{$LANG.descriptionUc}"
                            data-product-groups-enabled="{$defaults.product_groups}">
                        <option value=""></option>
                        {foreach $products as $product}
                            <option value="{if isset($product.id)}{$product.id|htmlSafe}{/if}"
                                {if isset($defaultInvoiceItems[$line].product_id) &&
                                    $product.id == $defaultInvoiceItems[$line].product_id}selected{/if}>
                                {$product.description|htmlSafe}
                            </option>
                        {/foreach}
                    </select>
                {/if}
            </td>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                {assign var="taxNumber" value=$smarty.section.tax.index }
                <td>
                    <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                            name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                            data-row-num="{$line|htmlSafe}" >
                        <option value=""></option>
                        {foreach $taxes as $tax}
                            <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}"
                                    {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                        $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}>{$tax.tax_description|htmlSafe}</option>
                        {/foreach}
                    </select>
                </td>
            {/section}
            <td>
                <input class="si_right {if $line == "0"}validate[required]{/if}" id="unit_price{$line|htmlSafe}"
                       name="unit_price{$line|htmlSafe}" size="7" data-row-num="{$line|htmlSafe}"
                       value="{if isset($defaultInvoiceItems[$line].unit_price)}{$defaultInvoiceItems[$line].unit_price|utilNumber}{/if}"/>
            </td>
        </tr>
        <tr class="details {if $defaults.invoice_description_open == $smarty.const.DISABLED}si_hide{/if}">
            <td></td>
            <td colspan="4">
                 <textarea name="description{$line|htmlSafe}" id="description{$line|htmlSafe}" data-description="{$LANG.descriptionUc}" data-row-num="{$line|htmlSafe}"
                           rows="4" cols="60">{if isset($defaultInvoiceItems[$line].description)}{$defaultInvoiceItems[$line].description|htmlSafe}{/if}</textarea>
            </td>
        </tr>
        </tbody>
    {/section}
</table>
