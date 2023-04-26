<div class="grid__area {if $even == 1}even{else}odd{/if}">
    <div class="grid__container grid__head-10">
        <div class="align__text-right pad__right-1">{$invoiceItem.quantity|utilNumberTrim:$invoice.precision:$locale}</div>
        <div class="cols__2-span-5">{$invoiceItem.product.description|htmlSafe}</div>
        <div class="cols__7-span-2 align__text-right">{$invoiceItem.unit_price|utilCurrency:$locale:$currencyCode}</div>
        <div class="cols__9-span-2 align__text-right">{$invoiceItem.gross_total|utilCurrency:$locale:$currencyCode}</div>
    </div>
    {if isset($invoiceItem.attributeDecode)}
        {$firstAttributeFound = false}
            {$begCol = 2}
            {foreach $invoiceItem.attributeDecode as $decodeKey => $decodeItem}
                {foreach $invoiceItem.productAttributes as $productAttribute}
                    {if ($productAttribute.enabled == $smarty.const.ENABLED)}
                        {if !$firstAttributeFound}
                            {* Done this way so space before descirption minimized of no attributes. *}
                            <div class="grid__container grid__head-10">
                            {$firstAttributeFound = true}
                        {/if}
                        {if $productAttribute.id == $decodeKey}
                            {if $productAttribute.type == 'list'}
                                {foreach $productAttribute.attrVals as $attrValKey => $attrValItem}
                                    {if $decodeItem == $attrValItem.id}
                                        {$name = $productAttribute.name}
                                        {$value = $attrValItem.value}
                                        {break}
                                    {/if}
                                {/foreach}
                            {elseif $productAttribute.type == 'decimal'}
                                {$name = $productAttribute.name}
                                {$value = $decodeItem|utilCurrency:$locale:$currencyCode}
                            {else}
                                {$name = $productAttribute.name}
                                {$value = $decodeItem}
                            {/if}
                            <div class="cols__{$begCol}-span-1 bold">
                                {$name}:&nbsp;
                            </div>
                            <div class="cols__{$begCol+1}-span-2">
                                {$value}
                            </div>
                            {$begCol = $begCol + 3}
                            {break}
                        {/if}
                    {/if}
                {/foreach}
            {/foreach}
        {if $firstAttributeFound}
            </div>
        {/if}
    {/if}
    {if isset($invoiceItem.description)}
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-9 fullItemized" style="display:none;">{$invoiceItem.description|htmlSafe}</div>
        </div>
    {/if}
    <div class="fullItemized" style="display:none;">
        {if !empty($customFieldLabels.product_cf1) && !empty($invoiceItem.product.custom_field1)}
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-2 bold">{$customFieldLabels.product_cf1|htmlSafe}:</div>
                <div class="cols__4-span-6">{$invoiceItem.product.custom_field1|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.product_cf2) && !empty($invoiceItem.product.custom_field2)}
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-2 bold">{$customFieldLabels.product_cf2|htmlSafe}:</div>
                <div class="cols__4-span-6">{$invoiceItem.product.custom_field2|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.product_cf3) && !empty($invoiceItem.product.custom_field3)}
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-2 bold">{$customFieldLabels.product_cf3|htmlSafe}:</div>
                <div class="cols__4-span-6">{$invoiceItem.product.custom_field3|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.product_cf4) && !empty($invoiceItem.product.custom_field4)}
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-2 bold">{$customFieldLabels.product_cf4|htmlSafe}:</div>
                <div class="cols__4-span-6">{$invoiceItem.product.custom_field|htmlSafe}</div>
            </div>
        {/if}
    </div>
</div>
