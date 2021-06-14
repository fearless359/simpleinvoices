{*
 *  Script: details.tpl
 *    Invoice details template
 *
 *  Modified for 'default_invoices' by Marcel van Dorp. Version 20090208
 *    if no invoice_id set, the date will be today, and the action will be
 *    'insert' instead of 'edit'
 *  Modified by Rich Rowley 20160212 to fix missing closing <td> tag and format for readability.
 *  Modified by Rich Rowley 20181023 to support addition of default_invoice to standard app.
 *
 *  License:
 *    GPL v3 or above
 *
 *  Website:
 *    https://simpleinvoices.group *}

<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
    <div id="itemtable" class='grid__area'>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}:</div>
            <div class="cols__3-span-8">{if !$invoice.id}{$LANG.copiedFrom}&nbsp;{/if}{$invoice.index_id|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold si_label">{$LANG.dateFormatted}:</div>
            {if !isset($invoice.id)}
                <div class="cols__3-span-8">
                    <input type="text" size="10" class="date-picker" name="date" id="date1"
                           value="{$smarty.now|date_format:'%Y-%m-%d'}"/>
                </div>
            {else}
                <div class="cols__3-span-8">
                    <input type="text" size="10" class="date-picker" name="date" id="date1"
                           value="{if isset($invoice.calc_date)}{$invoice.calc_date|htmlSafe}{/if}"/>
                </div>
            {/if}
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold si_label">{$LANG.billerUc}:</div>
            <div class="cols__3-span-8">
                {if !isset($billers) }
                    <em>{$LANG.noBillers}</em>
                {else}
                    <select name="biller_id">
                        {foreach $billers as $biller}
                            <option {if $biller.id == $invoice.biller_id} selected {/if}
                                    value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">{$biller.name|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold si_label">{$LANG.customerUc}:</div>
            <div class="cols__3-span-8">
                {if !isset($customers)}
                    <em>{$LANG.noCustomers}</em>
                {else}
                    <select name="customer_id">
                        {foreach $customers as $customer}
                            <option {if $customer.id == $invoice.customer_id} selected {/if}
                                    value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">{$customer.name|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
        </div>
        {if $invoice.type_id == TOTAL_INVOICE }
        <input type="hidden" name="quantity0" value="1"/>
        <input type="hidden" name="id0" value="{$invoiceItems[0].id|htmlSafe}"/>
        <input type="hidden" name="products0" value="{$invoiceItems[0].product_id|htmlSafe}"/>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10 bold">{$LANG.descriptionUc}:</div>
            <div class="cols__1-span-10">
                <textarea name="description0" id="description0" style="overflow:scroll;" rows="3" cols="100%"
                          data-row-num="0" data-description="{$LANG.descriptionUc}">{$invoiceItems[0].description|outHtml}</textarea>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold si_label">{$LANG.grossTotal}:</div>
            <div class="cols__3-span-3">
                <input type="text" class="si_right  validate[required]" name="unit_price0"
                       value="{$invoiceItems[0].unit_price|utilNumber}" size="10"/>
            </div>
            {if $defaults.tax_per_line_item > 0}
                <div class="cols__6-span-1 bold si_right si_label">{$LANG.tax}:&nbsp;</div>
                {section name=tax loop=$defaults.tax_per_line_item}
                    {$index = $smarty.section.tax.index}
                    {$taxNumber = $invoiceItems[0].tax.$index}
                    {$colStart = $smarty.section.tax.index + 7}
                    <div class="cols__{$colStart}-span-1 si_margin__right-1">
                        <select id="tax_id[0][{$smarty.section.tax.index|htmlSafe}]"
                                name="tax_id[0][{$smarty.section.tax.index|htmlSafe}]">
                            <option value=""></option>
                            {assign var="index" value=$smarty.section.tax.index}
                            {foreach $taxes as $tax}
                                <option {if isset($taxNumber) && $tax.tax_id == $taxNumber}selected{/if}
                                        value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </div>
                {/section}
            {/if}
        </div>
        <br/>
        {$customFields.1}
        {$customFields.2}
        {$customFields.3}
        {$customFields.4}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.invPref}:</div>
            <div class="cols__3-span-3">
                {if !isset($preferences) }
                    <em>{$LANG.noPreferences}</em>
                {else}
                    <select name="preference_id">
                        {foreach $preferences as $preference}
                            <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                    value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            <div class="cols__6-span-2 bold">{$LANG.salesRepresentative}:</div>
            <div class="cols__8-span-3">
                <input id="sales_representative}" name="sales_representative" size="30"
                       value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
            </div>
        </div>
        {elseif $invoice.type_id == ITEMIZED_INVOICE }
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-1 bold si_right si_margin__right-1">{$LANG.quantity}</div>
            <div class="cols__2-span-5 bold si_center si_margin__right-1">{$LANG.descriptionUc}</div>
            {section name=tax_header loop=$defaults.tax_per_line_item }
                <div class="cols__{$smarty.section.tax_header.index+7}-span-1 bold si_center si_margin__right-1">{$LANG.tax} {if $defaults.tax_per_line_item > 1} {$smarty.section.tax_header.iteration|htmlSafe}{/if}</div>
            {/section}
            <div class="cols__{$defaults.tax_per_line_item+7}-span-2 bold si_center si_margin__right-1">{$LANG.unitPrice}</div>
        </div>
        {foreach $invoiceItems as $line => $invoiceItem}
            <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
            <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"
                   value="{if isset($invoiceItem.id)}{$invoiceItem.id|htmlSafe}{/if}"/>
            <div class="line_item grid__container grid__head-10" id="row{$line|htmlSafe}">
                <div class="cols__1-span-1">
                    <div style="display:grid;grid-template-columns: 9% 7% 84%;">
                        {if $line == "0"}&nbsp;
                        {else}
                        <a class="delete_link" id="delete_link{$line|htmlSafe}" href="#" title="{$LANG.deleteLineItem}"
                           style="display:{if $line == "0"}none{else}inline{/if};"
                           data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}>
                            <img id="delete_image{$line|htmlSafe}" src="images/delete_item.png" alt="{$LANG.deleteLineItem}"
                                 style="margin-top: .5rem;"/>
                        </a>
                        {/if}
                        <span>&nbsp;</span>
                        <input class="si_right{if $line == 0} validate[required,min[.01],custom[number]]{/if}"
                               type="text" name="quantity{$line|htmlSafe}" id="quantity{$line|htmlSafe}" size="5"
                               data-row-num="{$line|htmlSafe}"
                               value='{$invoiceItem.quantity|utilNumberTrim}'/>
                    </div>
                </div>
                <div class="cols__2-span-5">
                    {if !isset($products) }
                        <em>{$LANG.noProducts}</em>
                    {else}
                        <select name="products{$line|htmlSafe}" id="products{$line|htmlSafe}"
                                class="si_input product_change width_95{if $line == 0} validate[required]{/if}"
                                data-row-num="{$line|htmlSafe}" data-description="{$LANG.descriptionUc}"
                                data-product-groups-enabled="{$defaults.product_groups}">
                            {foreach $products as $product}
                                <option {if $product.id == $invoiceItem.product_id} selected {/if}
                                        value="{if isset($product.id)}{$product.id|htmlSafe}{/if}">{$product.description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                {section name=tax loop=$defaults.tax_per_line_item}
                    {$colStart = $smarty.section.tax.index + 7}
                    <div class="cols__{$colStart}-span-1 si_margin__right-1">
                        <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                data-row-num="{$line|htmlSafe}">
                            <option value=""></option>
                            {$index = $smarty.section.tax.index}
                            {foreach $taxes as $tax}
                                <option {if isset($invoiceItem.tax) && $tax.tax_id == $invoiceItem.tax.$index} selected {/if}
                                        value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    </div>
                {/section}
                <div class="cols__{$defaults.tax_per_line_item+7}-span-1">
                    <input type="text" id="unit_price{$line|htmlSafe}" name="unit_price{$line|htmlSafe}" size="7"
                           data-row-num="{$line|htmlSafe}" value="{$invoiceItem.unit_price|utilNumber}"/>
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
                            <label for="list{$line}{$prodAttrVal.id}" class="bold">{$prodAttrVal.name}:</label>
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
                            <label for="free{$line}{$prodAttrVal.id}" class="bold">{$prodAttrVal.name}:</label>
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
                            <label for="decimal{$line}{$prodAttrVal.id}" class="bold">{$prodAttrVal.name}:</label>
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
            <div class="grid__container grid__head-10 details {if $defaults.invoice_description_open == $smarty.const.DISABLED}si_hide{/if}">
                {* colspan intentionally greater than min and max number so always uses full size *}
                <div class="cols__1-span-10">
                    <textarea name="description{$line|htmlSafe}" style="overflow:scroll;"
                              id="description{$line|htmlSafe}" rows="3" cols="100"
                              data-row-num="{$line|htmlSafe}"
                              data-description="{$LANG.descriptionUc}">{$invoiceItem.description|outHtml}</textarea>
                </div>
            </div>
        {/foreach}
        {include file="$path/invoiceItemsShowHide.tpl" }
        {$customFields.1}
        {$customFields.2}
        {$customFields.3}
        {$customFields.4}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10">{$LANG.notes}:</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class='cols__1-span-10 si_invoice_notes'>
                <input name="note" id="note" {if isset($invoice.note)}value="{$invoice.note|outHtml}"{/if} type="hidden">
                <trix-editor input="note"></trix-editor>
            </div>
        </div>
        <div class="grid__container grid_head-10">
            <div class="cols__1-span-2 bold">{$LANG.invPref}:</div>
            <div class="cols__3-span-3">
                {if !isset($preferences) }
                    <em>{$LANG.noPreferences}</em>
                {else}
                    <select name="preference_id">
                        {foreach $preferences as $preference}
                            <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                    value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            <div class="cols__6-span-2 bold">{$LANG.salesRepresentative}:</div>
            <div class="cols__8-span-3">
                <input type="text" id="sales_representative}" name="sales_representative" size="30"
                       value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
            </div>
        </div>
        {/if}

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=invoices&amp;view=manage" class="negative">
                <img src="images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="id" value="{$invoice.id|htmlSafe}"/>
        <input type="hidden" name="op" value="edit"/>
        {if $invoice.type_id == TOTAL_INVOICE }
            <input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
            <input id="line_item0" type="hidden" value="{$invoiceItems[0].id|htmlSafe}" name="line_item0"/>
        {/if}
        <input type="hidden" name="type" value="{if isset($invoice.type_id)}{$invoice.type_id|htmlSafe}{/if}"/>
        <input type="hidden" id="max_items" name="max_items" value="{if isset($lines)}{$lines|htmlSafe}{/if}"/>
    </div>
</form>
