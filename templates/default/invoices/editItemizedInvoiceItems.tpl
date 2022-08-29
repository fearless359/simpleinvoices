<div class="grid__container grid__head-10">
    <div class="cols__1-span-1 bold align__text-right">
        <label for="quantity0">{$LANG.quantityShort}
            <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpQuantity}" src="{$helpImagePath}required-small.png" alt=""/>
        </label>
    </div>
    <div class="cols__2-span-4 bold align__text-center">{$LANG.item}</div>
    {$begCol = 6}
    {section name=tax_header loop=$defaults.tax_per_line_item }
        <div class="cols__{$begCol}-span-1 bold align__text-center">{$LANG.tax}{if $defaults.tax_per_line_item > 1} {$smarty.section.tax_header.iteration|htmlSafe}{/if}</div>
        {$begCol = $begCol + 1}
    {/section}
    <div class="cols__{$begCol}-span-1 bold align__text-right">{$LANG.unitPrice}</div>
</div>
<div id="itemtable" data-number-tax-items="{$defaults.tax_per_line_item}">
    {foreach $invoiceItems as $line => $invoiceItem}
        <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
        <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"
               value="{if isset($invoiceItem.id)}{$invoiceItem.id|htmlSafe}{/if}"/>
        <div class="lineItem" id="row{$line|htmlSafe}">
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-1">
                    {if $line == "0"}
                        {$cols = "16% 84%"} {* Account for image not displaying on first line. *}
                    {else}
                        {$cols = "9% 7% 84%"}
                   {/if}
                   <div id="qtyColumn" style="display:grid;grid-template-columns: {$cols};">
                        <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" title="{$LANG.deleteLineItem}"
                           {if $line == "0"}style="display:none;"{/if}
                           data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}>
                            <img id="delete_image{$line|htmlSafe}" class="margin__top-0-5"
                                 src="images/delete_item.png" alt="{$LANG.deleteLineItem}"/>
                        </a>
                        <span>&nbsp;</span>
                        <!--suppress HtmlFormInputWithoutLabel -->
                       <input type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" {if $line == 0}required{/if}
                              class="align__text-right {if $line == 0}validate-quantity{/if}"
                              {if $line == 0}required{/if} data-row-num="{$line|htmlSafe}"
                               value='{$invoiceItem.quantity|utilNumberTrim}'/>
                    </div>
                </div>
                <div class="cols__2-span-4">
                    {if !isset($products) }
                        <em>{$LANG.noProducts}</em>
                    {else}
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select name="products{$line|htmlSafe}" id="products{$line|htmlSafe}"
                                class="product_change width_100 margin__left-0-5" {if $line == 0}required{/if}
                                data-row-num="{$line|htmlSafe}" data-description="{$LANG.descriptionUc}"
                                data-product-groups-enabled="{$defaults.product_groups}">
{var_dump($products)}
                            {foreach $products as $product}
                                <option {if $product.id == $invoiceItem.product_id} selected {/if}
                                        value="{if isset($product.id)}{$product.id|htmlSafe}{/if}">{$product.description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                {$begCol = 6}
                {section name=tax loop=$defaults.tax_per_line_item}
                    <div class="cols__{$begCol}-span-1">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                data-row-num="{$line|htmlSafe}" class="margin__left-1">
                            <option value=""></option>
                            {$index = $smarty.section.tax.index}
                            {foreach $taxes as $tax}
                                <option {if isset($invoiceItem.tax) && $tax.tax_id == $invoiceItem.tax.$index} selected {/if}
                                        value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </div>
                    {$begCol = $begCol + 1}
                {/section}
                <div class="cols__{$begCol}-span-1">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <input type="text" class="align__text-right margin__left-1" id="unit_price{$line|htmlSafe}" name="unit_price{$line|htmlSafe}" size="9"
                           {if $line == "0"}required{/if} data-row-num="{$line|htmlSafe}"
                           value="{$invoiceItem.unit_price|utilNumber}"/>
                </div>
            </div>

            {foreach $invoiceItem.productAttributes as $prodAttrVal}
                {if $prodAttrVal@first}
                    <div class="grid__container grid__head-10">
                    {$begCol = 2}
                {/if}

                {if $invoiceItem.product.enabled == $smarty.const.ENABLED}
                    {if $prodAttrVal.type == 'list'}
                        <div class="cols__{$begCol}-span-2">
                            <label for="list{$line}{$prodAttrVal.id}" class="">{$prodAttrVal.name}:</label>
                            <select name="attribute{$line}[{$prodAttrVal.id}]" id="list{$line}{$prodAttrVal.id}">
                                <option value=""></option>
                                {foreach $prodAttrVal.attrVals as $key => $val}
                                    {if $prodAttrVal.enabled == $smarty.const.ENABLED}
                                        {$selected = ""}
                                        {foreach $invoiceItem.attributeDecode as $decodeKey => $decodeItem}
                                            {if $prodAttrVal.id == $decodeKey && $val.id == $decodeItem}
                                                {$selected = "selected"}
                                                {break}
                                            {/if}
                                        {/foreach}
                                        <option {$selected} value="{$val.id}">{$val.value}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        {$begCol = $begCol + 2}
                    {elseif $prodAttrVal.type == 'free'}
                        {$attributeValue = ''}
                        {foreach $invoiceItem.attributeDecode as $decodeKey => $decodeItem}
                            {if $prodAttrVal.id == $decodeKey}
                                {$attributeValue = $decodeItem}
                                {break}
                            {/if}
                        {/foreach}
                        <div class='cols__{$begCol}-span-2'>
                            <label for="free{$line}{$prodAttrVal.id}" class="">{$prodAttrVal.name}:</label>
                            <input type="text" name="attribute{$line}[{$prodAttrVal.id}]"
                                   id="free{$line}{$prodAttrVal.id}" value="{$attributeValue}"/>
                        </div>
                        {$begCol = $begCol + 2}
                    {elseif $prodAttrVal.type == 'decimal'}
                        {$attributeValue = ''}
                        {foreach $invoiceItem.attributeDecode as $decodeKey => $decodeItem}
                            {if $prodAttrVal.id == $decodeKey}
                                {$attributeValue = $decodeItem}
                                {break}
                            {/if}
                        {/foreach}
                        <div class='cols__{$begCol}-span-2'>
                            <label for="decimal{$line}{$prodAttrVal.id}" class="">{$prodAttrVal.name}:</label>
                            <input type="text" name="attribute{$line}[{$prodAttrVal.id}]" size="5"
                                   id="decimal{$line}{$prodAttrVal.id}" value="{$attributeValue}"/>
                        </div>
                        {$begCol = $begCol + 2}
                    {/if}
                    {if $prodAttrVal@last}
                        </div>
                    {/if}
                {/if}
            {/foreach}
            <div class="grid__container grid__head-10 details"
                 {if $defaults.invoice_description_open == $smarty.const.DISABLED}style="display:none;"{/if}>
                {* colspan intentionally greater than min and max number so always uses full size *}
                <div class="cols__2-span-9">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <textarea name="description{$line|htmlSafe}" id="description{$line|htmlSafe}" rows="3" cols="99"
                              class="margin__left-0-5" data-row-num="{$line|htmlSafe}"
                              data-description="{$LANG.descriptionUc}">{$invoiceItem.description|outHtml}</textarea>
                </div>
            </div>
        </div>
    {/foreach}
</div>
{include file="$path/invoiceItemsShowHide.tpl" }
{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div class="grid__container grid__head-10">
    <div class="cols__1-span-2 bold">{$LANG.notes}:</div>
</div>
<div class="grid__container grid__head-10">
    <div class='cols__1-span-9'>
        <input name="note" id="note" {if isset($invoice.note)}value="{$invoice.note|outHtml}"{/if} type="hidden">
        <trix-editor input="note"></trix-editor>
    </div>
</div>
<div class="grid__container grid_head-10">
    <label for="preferenceId" class="cols__1-span-2 align__text-right margin__right-1">{$LANG.invPref}:</label>
    <div class="cols__3-span-3">
        {if !isset($preferences) }
            <em>{$LANG.noPreferences}</em>
        {else}
            <select name="preference_id" id="preferenceId">
                {foreach $preferences as $preference}
                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                {/foreach}
            </select>
        {/if}
    </div>
    <label for="salesRepresentativeId" class="cols__6-span-2 align__text-right margin__right-1">{$LANG.salesRepresentative}:</label>
    <div class="cols__8-span-3">
        <input type="text" name="sales_representative" id="salesRepresentativeId" size="30"
               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
    </div>
</div>
