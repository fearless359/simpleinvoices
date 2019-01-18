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
    <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..."/>
    {$LANG.loading} ...
</div>
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=invoices&amp;view=save">
    <div class="si_invoice_form">
        <table class='si_invoice_top'>
            <tr>
                <th>{$preference.pref_inv_wording|htmlsafe} {$LANG.number_short}</th>
                <td>{if !$invoice.id}{$LANG.copied_from}&nbsp;{/if}{$invoice.index_id|htmlsafe}</td>
            </tr>
            <tr>
                <th>{$LANG.date_formatted}</th>
                {if !isset($invoice.id)}
                    <td>
                        <input type="text" size="10" class="date-picker" name="date" id="date1"
                               value="{$smarty.now|date_format:'%Y-%m-%d'}"/>
                    </td>
                {else}
                    <td>
                        <input type="text" size="10" class="date-picker" name="date" id="date1"
                               value="{if isset($invoice.calc_date)}{$invoice.calc_date|htmlsafe}{/if}"/>
                    </td>
                {/if}
            </tr>
            <tr>
                <th>{$LANG.biller}</th>
                <td>
                    {if !isset($billers) }
                        <em>{$LANG.no_billers}</em>
                    {else}
                        <select name="biller_id">
                            {foreach from=$billers item=biller}
                                <option {if $biller.id == $invoice.biller_id} selected {/if}
                                        value="{if isset($biller.id)}{$biller.id|htmlsafe}{/if}">{$biller.name|htmlsafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
            </tr>
            <tr>
                <th>{$LANG.customer}</th>
                <td>
                    {if !isset($customers)}
                        <em>{$LANG.no_customers}</em>
                    {else}
                        <select name="customer_id">
                            {foreach from=$customers item=customer}
                                <option {if $customer.id == $invoice.customer_id} selected {/if}
                                        value="{if isset($customer.id)}{$customer.id|htmlsafe}{/if}">{$customer.name|htmlsafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
            </tr>
            {* TODO: implement status
            <tr>
              <th>Invoice Status</th>
              <td>
                <select name="status_id">
                  <option value="0">New</option>
                  <option {if $invoice.status_id == 1} selected{/if} value="1">Sent</option>
                  <option {if $invoice.status_id == 2} selected{/if} value="1">Paid</option>
                </select>
              </td>
            </tr>
            *}
        </table>
        {if $invoice.type_id == TOTAL_INVOICE }
            <table id="itemtable" class="si_invoice_items">
                <tr>
                    <th class="left">{$LANG.description}</th>
                </tr>
                <tr>
                    <td class='si_invoice_notes' colspan="2">
                        <!--
                        <textarea class="editor" name="description0">{*$invoiceItems[0].description|outhtml*}</textarea>
                        -->
                        <input name="description0" id="description0" {if isset($invoiceItems[0].description)}value="{$invoiceItems[0].description|outhtml}"{/if} type="hidden">
                        <trix-editor input="description0"></trix-editor>
                    </td>
                </tr>
            </table>
            <table class="si_invoice_bot">
                <tr>
                    <th>{$LANG.gross_total}</th>
                    <td>
                        <input type="text" name="unit_price0"
                               value="{$invoiceItems[0].unit_price|siLocal_number_trim}" size="10"/>
                        <input type="hidden" name="quantity0" value="1"/>
                        <input type="hidden" name="id0" value="{$invoiceItems[0].id|htmlsafe}"/>
                        <input type="hidden" name="products0" value="{$invoiceItems[0].product_id|htmlsafe}"/>
                    </td>
                    <th>{$LANG.tax}</th>
                    <td>
                        <table class="si_invoice_taxes">
                            <tr>
                                {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                    <td>
                                        <select id="tax_id[0][{$smarty.section.tax.index|htmlsafe}]"
                                                name="tax_id[0][{$smarty.section.tax.index|htmlsafe}]">
                                            <option value=""></option>
                                            {assign var="index" value=$smarty.section.tax.index}
                                            {foreach from=$taxes item=tax}
                                                <option {if $tax.tax_id === $invoiceItems[0].tax.$index} selected {/if}
                                                        value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}">{$tax.tax_description|htmlsafe}</option>
                                            {/foreach}
                                        </select>
                                    </td>
                                {/section}
                            </tr>
                        </table>
                    </td>
                </tr>
                {$customFields.1}
                {$customFields.2}
                {$customFields.3}
                {$customFields.4}
                <tr>
                    <th>{$LANG.inv_pref}</th>
                    <td>
                        {if !isset($preferences) }
                            <em>{$LANG.no_preferences}</em>
                        {else}
                            <select name="preference_id">
                                {foreach from=$preferences item=preference}
                                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlsafe}{/if}">{$preference.pref_description|htmlsafe}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                    <th>{$LANG.sales_representative}</th>
                    <td>
                        <input id="sales_representative}" name="sales_representative" size="30"
                               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlsafe}{/if}"/>
                    </td>
                </tr>
            </table>
        {elseif $invoice.type_id == ITEMIZED_INVOICE || $invoice.type_id == CONSULTING_INVOICE }
            <table id="itemtable" class="si_invoice_items">
                <thead>
                <tr>
                    <th></th>
                    <th>{$LANG.quantity_short}</th>
                    <th>{$LANG.description}</th>
                    {section name=tax_header loop=$defaults.tax_per_line_item }
                        <th>{$LANG.tax}{if $defaults.tax_per_line_item > 1} {$smarty.section.tax_header.index+1|htmlsafe}{/if}</th>
                    {/section}
                    <th>{$LANG.unit_price}</th>
                </tr>
                </thead>
                {foreach $invoiceItems as $line => $invoiceItem}
                    <tbody class="line_item" id="row{$line|htmlsafe}">
                    <tr class="tr_{cycle name="rows" values="A,B"}">
                        <input type="hidden" id="delete{$line|htmlsafe}" name="delete{$line|htmlsafe}" size="3"/>
                        <input type="hidden" name="line_item{$line|htmlsafe}" id="line_item{$line|htmlsafe}"
                               value="{if isset($invoiceItem.id)}{$invoiceItem.id|htmlsafe}{/if}"/>
                        <td>
                            <a id="trash_link_edit{$line|htmlsafe}" class="trash_link_edit"
                               title="{$LANG.delete_line_item}" href="#" style="display:inline;"
                               rel="{$line|htmlsafe}" data_delete_line_item={$config->confirm->deleteLineItem}>
                                <img id="delete_image{$line|htmlsafe}"
                                     src="images/common/{if $line == "0"}blank.gif{else}delete_item.png{/if}" alt=""/>
                            </a>
                        </td>
                        <td>
                            <input class="si_right {if $line == 0}validate[required,min[.01],custom[number]]{/if}"
                                   type="text" name='quantity{$line|htmlsafe}' id='quantity{$line|htmlsafe}'
                                   value='{$invoiceItem.quantity|siLocal_number_trim}' size="5"/>
                        </td>
                        <td>
                            {if !isset($products) }
                                <em>{$LANG.no_products}</em>
                            {else}
                                <select name="products{$line|htmlsafe}" id="products{$line|htmlsafe}" style="width:100%;"
                                        rel="{$line|htmlsafe}" class="product_change" data-description="{$LANG.description}">
                                    {foreach from=$products item=product}
                                        <option {if $product.id == $invoiceItem.product_id} selected {/if}
                                                value="{if isset($product.id)}{$product.id|htmlsafe}{/if}">{$product.description|htmlsafe}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </td>
                        {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                            <td>
                                <select id="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                                        name="tax_id[{$line|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]">
                                    <option value=""></option>
                                    {assign var="index" value=$smarty.section.tax.index}
                                    {foreach from=$taxes item=tax}
                                        <option {if $tax.tax_id === $invoiceItem.tax.$index} selected {/if}
                                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlsafe}{/if}">{$tax.tax_description|htmlsafe}</option>
                                    {/foreach}
                                </select>
                            </td>
                        {/section}
                        <td>
                            <input class="si_right" id="unit_price{$line|htmlsafe}" name="unit_price{$line|htmlsafe}" size="7"
                                   value="{$invoiceItem.unit_price|siLocal_number_trim}"/>
                        </td>
                    </tr>
                    {$invoiceItem.html}
                    <tr class="details si_hide">
                        <td></td>
                        <td colspan="4">
                        <textarea name="description{$line|htmlsafe}" style="overflow:scroll;"
                                  id="description{$line|htmlsafe}" rows="3" cols="100%"
                                  data-description="{$LANG['description']}">{$invoiceItem.description|outhtml}</textarea>
                        </td>
                    </tr>
                    </tbody>
                {/foreach}
            </table>
            <div class="si_toolbar si_toolbar_inform">
                <a href="#" class="add_line_item" data-description="{$LANG.description}">
                    <img src="images/common/add.png" alt=""/>{$LANG.add_new_row}</a>
                <a href='#' class="show_details" title="{$LANG.show_details}">
                    <img src="images/common/page_white_add.png" alt=""/>
                    {$LANG.show_details}
                </a>
                <a href='#' class="hide_details si_hide" title="{$LANG.hide_details}">
                    <img src="images/common/page_white_delete.png" alt=""/>
                    {$LANG.hide_details}
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
                        <!--
                        <textarea class="editor" name="note">{*$invoice.note|outhtml*}</textarea>
                        -->
                        <input name="note" id="note" {if isset($invoice.note)}value="{$invoice.note|outhtml}"{/if} type="hidden">
                        <trix-editor input="note"></trix-editor>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.inv_pref}</th>
                    <td>
                        {if !isset($preferences) }
                            <em>{$LANG.no_preferences}</em>
                        {else}
                            <select name="preference_id">
                                {foreach from=$preferences item=preference}
                                    <option {if $preference.pref_id == $invoice.preference_id} selected {/if}
                                            value="{if isset($preference.pref_id)}{$preference.pref_id|htmlsafe}{/if}">{$preference.pref_description|htmlsafe}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                    <th>{$LANG.sales_representative}</th>
                    <td>
                        <input id="sales_representative}" name="sales_representative" size="30"
                               value="{if isset($invoice.sales_representative)}{$invoice.sales_representative|htmlsafe}{/if}"/>
                    </td>
                </tr>
            </table>
        {/if}

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=invoices&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <div>
        {if !isset($invoice.id)}
            <input type="hidden" name="action" value="insert"/>
        {else}
            <input type="hidden" name="id" value="{if isset($invoice.id)}{$invoice.id|htmlsafe}{/if}"/>
            <input type="hidden" name="action" value="edit"/>
        {/if}
        {if $invoice.type_id == TOTAL_INVOICE }
            <input id="quantity0" type="hidden" size="10" value="1.00" name="quantity0"/>
            <input id="line_item0" type="hidden" value="{$invoiceItems[0].id|htmlsafe}" name="line_item0"/>
        {/if}
        <input type="hidden" name="type" value="{if isset($invoice.type_id)}{$invoice.type_id|htmlsafe}{/if}"/>
        <input type="hidden" name="op" value="insert_preference"/>
        <input type="hidden" id="max_items" name="max_items" value="{if isset($lines)}{$lines|htmlsafe}{/if}"/>
    </div>
</form>
