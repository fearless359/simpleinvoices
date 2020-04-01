<table id="itemtable" class="si_invoice_items">
    <thead>
    <tr>
        <th class=""></th>
        <th class="">{$LANG.quantity}</th>
        <th class="">{$LANG.item}</th>
        {section name=tax_header loop=$defaults.tax_per_line_item }
            <th class="">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if} </th>
        {/section}
        <th class="">{$LANG.unit_price}</th>
    </tr>
    </thead>
    {section name=line start=0 loop=$dynamic_line_items step=1}
        {assign var="line" value=$smarty.section.line.index }
        <tbody class="line_item" id="row{$line|htmlsafe}">
        <tr>
            <input type="hidden" id="delete{$line|htmlsafe}" name="delete{$line|htmlsafe}"/>
            <input type="hidden" name="line_item{$line|htmlsafe}" id="line_item{$line|htmlsafe}"/> {* id of invoice_items record *}
            <td>
                <a class="delete_link" id="delete_link{$line|htmlsafe}" href="#" style="display: {if $line == 0}none{else}inline{/if};"
                   data-row-num="{$line|htmlsafe}" data-delete-line-item={$config->confirm->deleteLineItem}
                   title="{$LANG.delete_row|htmlsafe}">
                    <img id="delete_image{$line|htmlsafe}" src="images/common/delete_item.png" height="16px" width="16px" alt=""/>
                </a>
            </td>
            <td>
                <input class="si_right{if $line == 0} validate[required,min[.01],custom[number]]{/if}"
                       type="text" name="quantity{$line|htmlsafe}" id="quantity{$line|htmlsafe}" size="5"
                       data-row-num="{$line|htmlsafe}"
                       value="{if isset($defaultInvoiceItems[$line].quantity)}{$defaultInvoiceItems[$line].quantity|siLocal_number_trim}{/if}" >
            </td>
            <td>
                {if !isset($products) }
                    <em>{$LANG.no_products}</em>
                {else}
                    <select id="products{$line|htmlsafe}" name="products{$line|htmlsafe}"
                            class="product_change{if $line == 0} validate[required]{/if}"
                            data-row-num="{$line|htmlsafe}" data-description="{$LANG.description}">
                        <option value=""></option>
                        {foreach from=$products item=product}
                            <option value="{if isset($product.id)}{$product.id|htmlsafe}{/if}"
                                {if isset($defaultInvoiceItems[$line].product_id) &&
                                    $product.id == $defaultInvoiceItems[$line].product_id}selected{/if}>
                                {$product.description|htmlsafe}
                            </option>
                        {/foreach}
                    </select>
                {/if}
            </td>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                {assign var="taxNumber" value=$smarty.section.tax.index }
                <td>
                    <select id="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                            name="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                            data-row-num="{$line|htmlsafe}" >
                        <option value=""></option>
                        {foreach from=$taxes item=tax}
                            <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}"
                                    {if isset($defaultInvoiceItems[$line].tax[$taxNumber]) &&
                                        $tax.tax_id == $defaultInvoiceItems[$line].tax[$taxNumber]}selected{/if}>{$tax.tax_description|htmlsafe}</option>
                        {/foreach}
                    </select>
                </td>
            {/section}
            <td>
                <input class="si_right {if $line == "0"}validate[required]{/if}" id="unit_price{$line|htmlsafe}"
                       name="unit_price{$line|htmlsafe}" size="7" data-row-num="{$line|htmlsafe}"
                       value="{if isset($defaultInvoiceItems[$line].unit_price)}{$defaultInvoiceItems[$line].unit_price|siLocal_number}{/if}"/>
            </td>
        </tr>
        <tr class="details si_hide">
            <td></td>
            <td colspan="4">
                 <textarea name="description{$line|htmlsafe}" id="description{$line|htmlsafe}" data-description="{$LANG['description']}" data-row-num="{$line|htmlsafe}"
                           rows="4" cols="60">{if isset($defaultInvoiceItems[$line].description)}{$defaultInvoiceItems[$line].description|htmlsafe}{/if}</textarea>
                {* Note that the space immediatly prior to the closing </textarea> tag is required to allow the description to display. Why??? I don't know!!! *}
            </td>
        </tr>
        </tbody>
    {/section}
</table>
