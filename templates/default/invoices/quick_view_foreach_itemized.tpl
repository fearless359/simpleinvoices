<tr>
    <td class="si_quantity">{$invoiceItem.quantity|siLocal_number_trim}</td>
    <td class="td_product" colspan="2">{$invoiceItem.product.description|htmlsafe}</td>
    <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
    <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
</tr>
{if isset($invoiceItem.attribute_json)}
    <tr class="si_product_attribute">
        <td></td>
        <td>
            <table>
                <tr class="si_product_attribute">
                    {foreach from=$invoiceItem.attribute_json key=k item=v}
                        <td class="si_product_attribute">
                            {if $v.type == 'decimal'}
                                {$v.name}:&nbsp;{$preference.pref_currency_sign}{$v.value|siLocal_number} ;
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
    <tr class="show-itemised tr_desc">
        <td></td>
        <td colspan="5" class="">
            {$invoiceItem.description|truncate:80:"...":true|htmlsafe}
        </td>
    </tr>
    <tr class="itemised tr_desc">
        <td></td>
        <td colspan="5" class="">{$invoiceItem.description|htmlsafe}</td>
    </tr>
{/if}
<tr class="itemised tr_custom">
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
