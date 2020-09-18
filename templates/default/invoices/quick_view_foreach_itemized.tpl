<tr>
    <td class="si_quantity">{$invoiceItem.quantity|utilNumberTrim}</td>
    <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlSafe}</td>
    <td class="si_right">{$invoiceItem.unit_price|utilCurrency:$locale:$currencyCode}</td>
    <td class="si_right">{$invoiceItem.gross_total|utilCurrency:$locale:$currencyCode}</td>
</tr>
{if isset($invoiceItem.attribute_json)}
    <tr class="si_product_attribute">
        <td></td>
        <td>
            <table>
                <tr class="si_product_attribute">
                    {foreach $invoiceItem.attribute_json as $k => $v}
                        <td class="si_product_attribute">
                            {if $v.type == 'decimal'}
                                {$v.name}:&nbsp;{$v.value|utilCurrency:$locale:$currencyCode} ;
                            {else}
                                {$v.name}:&nbsp;{$v.value} ;
                            {/if}
                        </td>
                    {/foreach}
                </tr>
            </table>
        </td>
    </tr>
{/if}
{if isset($invoiceItem.description)}
    <tr class="abbrev_itemised tr_desc">
        <td></td>
        <td colspan="5" class="">
            {$invoiceItem.description|truncate:80:"...":true|htmlSafe}
        </td>
    </tr>
    <tr class="full_itemised si_hide tr_desc">
        <td></td>
        <td colspan="5" class="">{$invoiceItem.description|htmlSafe}</td>
    </tr>
{/if}
<tr class="full_itemised si_hide tr_custom">
    <td></td>
    <td colspan="5">
        <table class="si_invoice_view_custom_items">
            <tr>
                {if !empty($customFieldLabels.product_cf1)}
                    <th>{$customFieldLabels.product_cf1|htmlSafe}:</th>
                    <td>{$invoiceItem.product.custom_field1|htmlSafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
                {if !empty($customFieldLabels.product_cf2)}
                    <th>{$customFieldLabels.product_cf2|htmlSafe}:</th>
                    <td>{$invoiceItem.product.custom_field2|htmlSafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
            </tr>
            <tr>
                {if !empty($customFieldLabels.product_cf3)}
                    <th>{$customFieldLabels.product_cf3|htmlSafe}:</th>
                    <td>{$invoiceItem.product.custom_field3|htmlSafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
                {if !empty($customFieldLabels.product_cf4)}
                    <th>{$customFieldLabels.product_cf4|htmlSafe}:</th>
                    <td>{$invoiceItem.product.custom_field4|htmlSafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
            </tr>
        </table>
    </td>
</tr>
