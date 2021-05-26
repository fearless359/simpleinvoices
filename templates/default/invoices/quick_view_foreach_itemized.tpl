<div class="grid__area {if $even == 1}even{else}odd{/if}">
    <div class="grid__head-10">
        <div class="si_right pad__right_1">{$invoiceItem.quantity|utilNumberTrim}</div>
        <div class="cols__2-span-5">{$invoiceItem.product.description|htmlSafe}</div>
        <div class="cols__7-span-2 si_right">{$invoiceItem.unit_price|utilCurrency:$locale:$currencyCode}</div>
        <div class="cols__9-span-2 si_right">{$invoiceItem.gross_total|utilCurrency:$locale:$currencyCode}</div>
    </div>
    {if isset($invoiceItem.attribute_json)}
        <div class="si_product_attribute">
            {foreach $invoiceItem.attribute_json as $k => $v}
                <div class="si_product_attribute">
                    {if $v.type == 'decimal'}
                        {$v.name}:&nbsp;{$v.value|utilCurrency:$locale:$currencyCode} ;
                    {else}
                        {$v.name}:&nbsp;{$v.value} ;
                    {/if}
                </div>
            {/foreach}
        </div>
    {/if}
    {if isset($invoiceItem.description)}
        <div class="grid__head-10">
            <div>&nbsp;</div>
            <div class="cols__2-span-9 abbrev_itemised">
                {$invoiceItem.description|truncate:80:"...":true|htmlSafe}
            </div>
            <div class="cols__2-span-9 full_itemised si_hide">
                {$invoiceItem.description|htmlSafe}
            </div>
        </div>
    {/if}
    <div class="full_itemised si_hide tr_custom">
        <div class="grid__head-10">
            {if !empty($customFieldLabels.product_cf1) && !empty($invoiceItem.product.custom_field1)}
                <div class="cols__1-span-2 bold">{$customFieldLabels.product_cf1|htmlSafe}:</div>
                <div class="cols__3-span-3">{$invoiceItem.product.custom_field1|htmlSafe}</div>
            {elseif !empty($customFieldLabels.product_cf2) && !empty($invoiceItem.product.custom_field2)}
                <div class="cols__1-span-5">&nbsp;</div>
            {/if}
            {if !empty($customFieldLabels.product_cf2) && !empty($invoiceItem.product.custom_field2)}
                <div class="cols__6-span-2 bold">{$customFieldLabels.product_cf2|htmlSafe}:</div>
                <div class="cols__8-span-3">{$invoiceItem.product.custom_field2|htmlSafe}</div>
            {/if}
        </div>
        <div class="grid__head-10">
            {if !empty($customFieldLabels.product_cf3) && !empty($invoiceItem.product.custom_field4)}
                <div class="cols__1-span-2 bold">{$customFieldLabels.product_cf3|htmlSafe}:</div>
                <div class="cols__3-span-3">{$invoiceItem.product.custom_field3|htmlSafe}</div>
            {elseif !empty($customFieldLabels.product_cf4) && !empty($invoiceItem.product.custom_field4)}
                <div class="cols__6-span-5">&nbsp;</div>
            {/if}
            {if !empty($customFieldLabels.product_cf4)}
                <div class="cols__6-span-2 bold">{$customFieldLabels.product_cf4|htmlSafe}:</div>
                <div class="cols__8-span-3">{$invoiceItem.product.custom_field4|htmlSafe}</div>
            {/if}
        </div>
    </div>
</div>
