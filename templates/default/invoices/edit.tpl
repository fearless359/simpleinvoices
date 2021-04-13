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

{* Still needed ?*}
<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
    <img src="images/gmail-loader.gif" alt="{$LANG.loading} ..."/>
    {$LANG.loading} ...
</div>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=invoices&amp;view=save">
    <div class="si_invoice_form">
        <table class='si_invoice_top'>
            <tr>
                <th>{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}</th>
                <td>{if !$invoice.id}{$LANG.copiedFrom}&nbsp;{/if}{$invoice.index_id|htmlSafe}</td>
            </tr>
            <tr>
                <th>{$LANG.dateFormatted}</th>
                {if !isset($invoice.id)}
                    <td>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" size="10" class="date-picker" name="date" id="date1"
                               value="{$smarty.now|date_format:'%Y-%m-%d'}"/>
                    </td>
                {else}
                    <td>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" size="10" class="date-picker" name="date" id="date1"
                               value="{if isset($invoice.calc_date)}{$invoice.calc_date|htmlSafe}{/if}"/>
                    </td>
                {/if}
            </tr>
            <tr>
                <th>{$LANG.billerUc}</th>
                <td>
                    {if !isset($billers) }
                        <em>{$LANG.noBillers}</em>
                    {else}
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select name="biller_id">
                            {foreach $billers as $biller}
                                <option {if $biller.id == $invoice.biller_id} selected {/if}
                                        value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">{$biller.name|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
            </tr>
            <tr>
                <th>{$LANG.customerUc}</th>
                <td>
                    {if !isset($customers)}
                        <em>{$LANG.noCustomers}</em>
                    {else}
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select name="customer_id">
                            {foreach $customers as $customer}
                                <option {if $customer.id == $invoice.customer_id} selected {/if}
                                        value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">{$customer.name|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
            </tr>
        </table>
        {if $invoice.type_id == TOTAL_INVOICE }
            <table id="itemtable" class="si_invoice_items">
                <tr>
                    <th class="left">{$LANG.descriptionUc}</th>
                </tr>
                <tr>
                    <td class='si_invoice_notes' colspan="5">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <textarea name="description0" style="overflow:scroll;"
                                  id="description0" rows="3" cols="100%"
                                  data-row-num="0"
                                  data-description="{$LANG.descriptionUc}">{$invoiceItems[0].description|outHtml}</textarea>
                    </td>
                </tr>
            </table>
            <table class="si_invoice_bot">
                <tr>
                    <th>{$LANG.grossTotal}</th>
                    <td>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" class="si_right" name="unit_price0"
                               value="{$invoiceItems[0].unit_price|utilNumber}" size="10"/>
                        <input type="hidden" name="quantity0" value="1"/>
                        <input type="hidden" name="id0" value="{$invoiceItems[0].id|htmlSafe}"/>
                        <input type="hidden" name="products0" value="{$invoiceItems[0].product_id|htmlSafe}"/>
                    </td>
                    {if $defaults.tax_per_line_item > 0}
                        <th>{$LANG.tax}</th>
                        <td>
                            <table class="si_invoice_taxes">
                                <tr>
                                    {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                        <td>
                                            <!--suppress HtmlFormInputWithoutLabel -->
                                            <select id="tax_id[0][{$smarty.section.tax.index|htmlSafe}]"
                                                    name="tax_id[0][{$smarty.section.tax.index|htmlSafe}]">
                                                <option value=""></option>
                                                {assign var="index" value=$smarty.section.tax.index}
                                                {foreach $taxes as $tax}
                                                    <option {if $tax.tax_id === $invoiceItems[0].tax.$index} selected {/if}
                                                            value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    {/section}
                                </tr>
                            </table>
                        </td>
                    {/if}
                </tr>
                {$customFields.1}
                {$customFields.2}
                {$customFields.3}
                {$customFields.4}
                <tr>
                    <th>{$LANG.invPref}</th>
                    <td>
                        {if !isset($preferences) }
                            <em>{$LANG.noPreferences}</em>
                        {else}
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="preference_id">
                                {foreach $preferences as $preference}
                                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                    <th>{$LANG.salesRepresentative}</th>
                    <td>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input id="sales_representative}" name="sales_representative" size="30"
                               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
                    </td>
                </tr>
            </table>
        {elseif $invoice.type_id == ITEMIZED_INVOICE || $invoice.type_id == CONSULTING_INVOICE }
            <table id="itemtable" class="si_invoice_items">
                <thead>
                <tr>
                    <th></th>
                    <th>{$LANG.quantityShort}</th>
                    <th>{$LANG.descriptionUc}</th>
                    {section name=tax_header loop=$defaults.tax_per_line_item }
                        <th>{$LANG.tax}{if $defaults.tax_per_line_item > 1} {$smarty.section.tax_header.index+1|htmlSafe}{/if}</th>
                    {/section}
                    <th>{$LANG.unitPrice}</th>
                </tr>
                </thead>
                {foreach $invoiceItems as $line => $invoiceItem}
                    <tbody class="line_item" id="row{$line|htmlSafe}">
                    <tr class="tr_{cycle name="rows" values="A,B"}">
                        <input type="hidden" id="delete{$line|htmlSafe}" name="delete{$line|htmlSafe}"/>
                        <input type="hidden" name="line_item{$line|htmlSafe}" id="line_item{$line|htmlSafe}"
                               value="{if isset($invoiceItem.id)}{$invoiceItem.id|htmlSafe}{/if}"/>
                        <td>
                            <a class="delete_link" id="delete_link{$line|htmlSafe}"
                               title="{$LANG.deleteLineItem}" href="#" style="display:{if $line == "0"}none{else}inline{/if};"
                               data-row-num="{$line|htmlSafe}" data-delete-line-item={$config.confirmDeleteLineItem}>
                                <img id="delete_image{$line|htmlSafe}" src="images/delete_item.png" alt=""/>
                            </a>
                        </td>
                        <td>
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input class="si_right{if $line == 0} validate[required,min[.01],custom[number]]{/if}"
                                   type="text" name='quantity{$line|htmlSafe}' id='quantity{$line|htmlSafe}'
                                   data-row-num="{$line|htmlSafe}"
                                   value='{$invoiceItem.quantity|utilNumberTrim}' size="5"/>
                        </td>
                        <td>
                            {if !isset($products) }
                                <em>{$LANG.noProducts}</em>
                            {else}
                                <!--suppress HtmlFormInputWithoutLabel -->
                                <select name="products{$line|htmlSafe}" id="products{$line|htmlSafe}"
                                        data-row-num="{$line|htmlSafe}"
                                        class="si_input product_change{if $line == 0} validate[required]{/if}"
                                        data-description="{$LANG.descriptionUc}"
                                        data-product-groups-enabled="{$defaults.product_groups}">
                                    {foreach $products as $product}
                                        <option {if $product.id == $invoiceItem.product_id} selected {/if}
                                                value="{if isset($product.id)}{$product.id|htmlSafe}{/if}">{$product.description|htmlSafe}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </td>
                        {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                            <td>
                                <!--suppress HtmlFormInputWithoutLabel -->
                                <select id="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]"
                                        data-row-num="{$line|htmlSafe}"
                                        name="tax_id[{$line|htmlSafe}][{$smarty.section.tax.index|htmlSafe}]">
                                    <option value=""></option>
                                    {assign var="index" value=$smarty.section.tax.index}
                                    {foreach $taxes  as $tax}
                                        <option {if isset($invoiceItem.tax) && $tax.tax_id === $invoiceItem.tax.$index} selected {/if}
                                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                                    {/foreach}
                                </select>
                            </td>
                        {/section}
                        <td>
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <input type="text" class="si_right" id="unit_price{$line|htmlSafe}" name="unit_price{$line|htmlSafe}" size="7"
                                   data-row-num="{$line|htmlSafe}" value="{$invoiceItem.unit_price|utilNumber}"/>
                        </td>
                    </tr>
                    {$invoiceItem.html}
                    <tr class="details {if $defaults.invoice_description_open == $smarty.const.DISABLED}si_hide{/if}">
                        <td></td>
                        <td colspan="4">
                        <!--suppress HtmlFormInputWithoutLabel -->
                            <textarea name="description{$line|htmlSafe}" style="overflow:scroll;"
                                  id="description{$line|htmlSafe}" rows="3" cols="100%"
                                  data-row-num="{$line|htmlSafe}"
                                  data-description="{$LANG.descriptionUc}">{$invoiceItem.description|outHtml}</textarea>
                        </td>
                    </tr>
                    </tbody>
                {/foreach}
            </table>
            <div class="si_toolbar si_toolbar_inform">
                <a href="#" class="add_line_item" data-description="{$LANG.descriptionUc}">
                    <img src="images/add.png" alt=""/>{$LANG.addNewRow}</a>
                <a href='#' class="show_details {if $defaults.invoice_description_open == $smarty.const.ENABLED}si_hide{/if}" title="{$LANG.showDetails}">
                    <img src="images/page_white_add.png" alt=""/>
                    {$LANG.showDetails}
                </a>
                <a href='#' class="hide_details {if $defaults.invoice_description_open == $smarty.const.DISABLED}si_hide{/if}" title="{$LANG.hideDetails}">
                    <img src="images/page_white_delete.png" alt=""/>
                    {$LANG.hideDetails}
                </a>
            </div>
            <table class="si_invoice_bot">
                {$customFields.1}
                {$customFields.2}
                {$customFields.3}
                {$customFields.4}
                <tr>
                    <th>{$LANG.notes}</th>
                </tr>
                <tr>
                    <td class='si_invoice_notes' colspan="4">
                        <input name="note" id="note" {if isset($invoice.note)}value="{$invoice.note|outHtml}"{/if} type="hidden">
                        <!--suppress HtmlUnknownTag -->
                        <trix-editor input="note"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.invPref}</th>
                    <td>
                        {if !isset($preferences) }
                            <em>{$LANG.noPreferences}</em>
                        {else}
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="preference_id">
                                {foreach $preferences as $preference}
                                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                    <th>{$LANG.salesRepresentative}</th>
                    <td>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input type="text" id="sales_representative}" name="sales_representative" size="30"
                               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlSafe}{/if}"/>
                    </td>
                </tr>
            </table>
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
    </div>
    <input type="hidden" name="id" value="{$invoice.id|htmlSafe}"/>
    <input type="hidden" name="op" value="edit"/>
    {if $invoice.type_id == TOTAL_INVOICE }
        <input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
        <input id="line_item0" type="hidden" value="{$invoiceItems[0].id|htmlSafe}" name="line_item0"/>
    {/if}
    <input type="hidden" name="type" value="{if isset($invoice.type_id)}{$invoice.type_id|htmlSafe}{/if}"/>
    <input type="hidden" id="max_items" name="max_items" value="{if isset($lines)}{$lines|htmlSafe}{/if}"/>
</form>
