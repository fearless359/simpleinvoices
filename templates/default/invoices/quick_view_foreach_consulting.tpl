<tr>
    <td class="si_quantity">{$invoiceItem.quantity|utilNumber}</td>
    <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlSafe}</td>
    <td class="si_right">{$invoiceItem.unit_price|utilCurrency:$locale:$currencyCode}</td>
    <td class="si_right">{$invoiceItem.gross_total|utilCurrency:$locale:$currencyCode}</td>
</tr>
<tr class="consulting tr_custom">
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
