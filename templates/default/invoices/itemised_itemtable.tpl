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
        {assign var="lineNumber" value=$smarty.section.line.index }
        <tbody class="line_item" id="row{$lineNumber|htmlsafe}">
        <tr>
            <td>
                {if $lineNumber == "0"}
                    <a href="#" title="{$LANG.cannot_delete_first_row|htmlsafe}"
                       class="trash_link" id="trash_link{$lineNumber|htmlsafe}"
                       data_delete_line_item={$config->confirm->deleteLineItem}>
                        <img id="trash_image{$lineNumber|htmlsafe}" title="{$LANG.cannot_delete_first_row}"
                             src="images/common/blank.gif" height="16px" width="16px" alt=""/>
                    </a>
                {else}
                    {* can't delete line 0 *}
                    <!-- onclick="delete_row({$lineNumber|htmlsafe});" -->
                    <a id="trash_link{$lineNumber|htmlsafe}" class="trash_link"
                       title="{$LANG.delete_row}" rel="{$lineNumber|htmlsafe}"
                       href="#" style="display: inline;" data_delete_line_item={$config->confirm->deleteLineItem}>
                        <img src="images/common/delete_item.png" alt=""/>
                    </a>
                {/if}
            </td>
            <td>
                <input class="si_right {if $lineNumber == "0"}validate[required,min[.01],custom[number]]{/if}"
                       type="text" name="quantity{$lineNumber|htmlsafe}"
                       id="quantity{$lineNumber|htmlsafe}" size="5"
                       value="{if isset($defaultInvoiceItems[$lineNumber].quantity)}{$defaultInvoiceItems[$lineNumber].quantity|siLocal_number_trim}{/if}" />
            </td>
            <td>
                {if !isset($products) }
                    <emjquery.vali>{$LANG.no_products}</emjquery.vali>
                {else}
                    <select id="products{$lineNumber|htmlsafe}"
                            name="products{$lineNumber|htmlsafe}"
                            rel="{$lineNumber|htmlsafe}"
                            class="{if $lineNumber == "0"}validate[required]{/if} product_change"
                            data-description="{$LANG.description}">
                        <option value=""></option>
                        {foreach from=$products item=product}
                            <option value="{if isset($product.id)}{$product.id|htmlsafe}{/if}"
                                {if isset($defaultInvoiceItems[$lineNumber].product_id) &&
                                    $product.id == $defaultInvoiceItems[$lineNumber].product_id}selected{/if}>
                                {$product.description|htmlsafe}
                            </option>
                        {/foreach}
                    </select>
                {/if}
            </td>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                {assign var="taxNumber" value=$smarty.section.tax.index }
                <td>
                    <select id="tax_id[{$lineNumber|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                            name="tax_id[{$lineNumber|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]">
                        <option value=""></option>
                        {foreach from=$taxes item=tax}
                            <option value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}"
                                    {if isset($defaultInvoiceItems[$lineNumber].tax[$taxNumber]) &&
                                        $tax.tax_id == $defaultInvoiceItems[$lineNumber].tax[$taxNumber]}selected{/if}>{$tax.tax_description|htmlsafe}</option>
                        {/foreach}
                    </select>
                </td>
            {/section}
            <td>
                <input class="si_right {if $lineNumber == "0"}validate[required]{/if}"
                       id="unit_price{$lineNumber|htmlsafe}"
                       name="unit_price{$lineNumber|htmlsafe}" size="7"
                       value="{if isset($defaultInvoiceItems[$lineNumber].unit_price)}{$defaultInvoiceItems[$lineNumber].unit_price|siLocal_number}{/if}"/>
            </td>
        </tr>
        <tr class="details si_hide">
            <td></td>
            <td colspan="4">
                 <textarea class="detail" name="description{$lineNumber|htmlsafe}" id="description{$lineNumber|htmlsafe}" data-description="{$LANG['description']}"
                           rows="4" cols="60">{if isset($defaultInvoiceItems[$lineNumber].description)}{$defaultInvoiceItems[$lineNumber].description|htmlsafe}{/if}</textarea>
                {* Note that the space immediatly prior to the closing </textarea> tag is required to allow the description to display. Why??? I don't know!!! *}
            </td>
        </tr>
        </tbody>
    {/section}
</table>
