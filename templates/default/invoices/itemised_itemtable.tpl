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
        <tbody class="line_item" id="row{$smarty.section.line.index|htmlsafe}">
        {assign var="lineNumber" value=$smarty.section.line.index }
        <tr>
            <td>
                {if $smarty.section.line.index == "0"}
                    <a href="#" title="{$LANG.cannot_delete_first_row|htmlsafe}"
                       class="trash_link" id="trash_link{$smarty.section.line.index|htmlsafe}"
                       data_delete_line_item={$config->confirm->deleteLineItem}>
                        <img id="trash_image{$smarty.section.line.index|htmlsafe}" title="{$LANG.cannot_delete_first_row}"
                             src="images/common/blank.gif" height="16px" width="16px" alt=""/>
                    </a>
                {else}
                    {* can't delete line 0 *}
                    <!-- onclick="delete_row({$smarty.section.line.index|htmlsafe});" -->
                    <a id="trash_link{$smarty.section.line.index|htmlsafe}" class="trash_link"
                       title="{$LANG.delete_row}" rel="{$smarty.section.line.index|htmlsafe}"
                       href="#" style="display: inline;" data_delete_line_item={$config->confirm->deleteLineItem}>
                        <img src="images/common/delete_item.png" alt=""/>
                    </a>
                {/if}
            </td>
            <td>
                <input class="si_right {if $smarty.section.line.index == "0"}validate[required]{/if}"
                       type="text" name="quantity{$smarty.section.line.index|htmlsafe}"
                       id="quantity{$smarty.section.line.index|htmlsafe}" size="5"
                       {if $smarty.get.quantity.$lineNumber}value="{$smarty.get.quantity.$lineNumber}"{/if} />
            </td>
            <td>
                {if $products == null }
                    <em>{$LANG.no_products}</em>
                {else}
                    <select id="products{$smarty.section.line.index|htmlsafe}"
                            name="products{$smarty.section.line.index|htmlsafe}"
                            rel="{$smarty.section.line.index|htmlsafe}"
                            class="{if $smarty.section.line.index == "0"}validate[required]{/if} product_change"
                            data_description="{$LANG.description}">
                        <option value=""></option>
                        {foreach from=$products item=product}
                            <option {if $product.id == $smarty.get.product.$lineNumber}
                                value="{$smarty.get.product.$lineNumber}" selected
                            {else}
                                value="{$product.id|htmlsafe}"
                            {/if}
                            >
                                {$product.description|htmlsafe}
                            </option>
                        {/foreach}
                    </select>
                {/if}
            </td>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                {assign var="taxNumber" value=$smarty.section.tax.index }
                <td>
                    <select id="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                            name="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]">
                        <option value=""></option>
                        {foreach from=$taxes item=tax}
                            <option {if $tax.tax_id == $smarty.get.tax.$lineNumber.$taxNumber}
                                value="{$smarty.get.tax.$lineNumber.$taxNumber}" selected
                            {else}
                                value="{$tax.tax_id|htmlsafe}"
                            {/if}
                            >
                                {$tax.tax_description|htmlsafe}
                            </option>
                        {/foreach}
                    </select>
                </td>
            {/section}
            <td>
                <input class="si_right {if $smarty.section.line.index == "0"}validate[required]{/if}"
                       id="unit_price{$smarty.section.line.index|htmlsafe}"
                       name="unit_price{$smarty.section.line.index|htmlsafe}" size="7"
                       value="{if $smarty.get.unit_price.$lineNumber}{$smarty.get.unit_price.$lineNumber}{/if}"/>
            </td>
        </tr>
        <tr class="details si_hide">
            <td></td>
            <td colspan="4">
                 <textarea class="detail" name="description{$smarty.section.line.index|htmlsafe}"
                           id="description{$smarty.section.line.index|htmlsafe}" rows="4" cols="60"
                           data_description="{$LANG['description']}"></textarea>
            </td>
        </tr>
        </tbody>
    {/section}
</table>
