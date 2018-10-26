<tr>
    <td class="si_quantity">{$invoiceItem.quantity|siLocal_number}</td>
    <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
    <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
    <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
</tr>
<tr class="consulting tr_custom">
    <td></td>
    <td colspan="5">
        <table class="si_invoice_view_custom_items">
            <tr>
                {if !empty($customFieldLabels.product_cf1)}
                    <th>{$customFieldLabels.product_cf1|htmlsafe}:</th>
                    <td>{$invoiceItem.product.custom_field1|htmlsafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
                {if !empty($customFieldLabels.product_cf2)}
                    <th>{$customFieldLabels.product_cf2|htmlsafe}:</th>
                    <td>{$invoiceItem.product.custom_field2|htmlsafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
            </tr>
            <tr>
                {if !empty($customFieldLabels.product_cf3)}
                    <th>{$customFieldLabels.product_cf3|htmlsafe}:</th>
                    <td>{$invoiceItem.product.custom_field3|htmlsafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
                {if !empty($customFieldLabels.product_cf4)}
                    <th>{$customFieldLabels.product_cf4|htmlsafe}:</th>
                    <td>{$invoiceItem.product.custom_field4|htmlsafe}</td>
                {else}
                    <td colspan="2"></td>
                {/if}
            </tr>
        </table>
    </td>
</tr>
