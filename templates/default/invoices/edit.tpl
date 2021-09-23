{*
 *  Script: edit.tpl
 *      Invoice edit template
 *
 *  Last Modified:
 *      20210615 by Rich Rowley to use grid layout rather than tables.
 *      20181023 by Rich Rowley to support addition of default_invoice to standard app.
 *      20160212 by Rich Rowley to fix missing closing <td> tag and format for readability.
 *      20090208 by by Marcel van Dorp for 'default_invoices'. If no invoice_id set,
 *          the date will be today, and the action will be 'insert' instead of 'edit'
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
    <div class='grid__area'>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}:</div>
            <div class="cols__3-span-8">{if !$invoice.id}{$LANG.copiedFrom}&nbsp;{/if}{$invoice.index_id|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="dateId" class="cols__1-span-2 align__text-right margin__right-1">{$LANG.dateFormatted}:</label>
            <div class="cols__3-span-8">
                <input type="text" size="10" class="date-picker" name="date" id="dateId" required readonly
                       value="{$invoice.date|htmlSafe}"/>
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="billerId" class="cols__1-span-2 align__text-right margin__right-1">{$LANG.billerUc}:</label>
            <div class="cols__3-span-8">
                {if !isset($billers) }
                    <em>{$LANG.noBillers}</em>
                {else}
                    <select name="biller_id" id="billerId">
                        {foreach $billers as $biller}
                            <option {if $biller.id == $invoice.biller_id} selected {/if}
                                    value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">{$biller.name|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <label for="customerId" class="cols__1-span-2 align__text-right margin__right-1">{$LANG.customerUc}:</label>
            <div class="cols__3-span-8">
                {if !isset($customers)}
                    <em>{$LANG.noCustomers}</em>
                {else}
                    <select name="customer_id" id="customerId">
                        {foreach $customers as $customer}
                            <option {if $customer.id == $invoice.customer_id} selected {/if}
                                    value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">{$customer.name|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
        </div>
        {if $invoice.type_id == TOTAL_INVOICE }
            {include file="$path/editTotalInvoiceItems.tpl" }
        {elseif $invoice.type_id == ITEMIZED_INVOICE }
            {include file="$path/editItemizedInvoiceItems.tpl" }
        {/if}

        <div class="align__text-center">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=invoices&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="id" value="{$invoice.id|htmlSafe}"/>
        <input type="hidden" name="op" value="edit"/>
        {if $invoice.type_id == TOTAL_INVOICE }
            <input type="hidden" id="quantity0" size="10" value="1.00" name="quantity0"/>
            <input type="hidden" id="line_item0" value="{$invoiceItems[0].id|htmlSafe}" name="line_item0"/>
            <input type="hidden" name="id0" value="{$invoiceItems[0].id|htmlSafe}"/>
            <input type="hidden" name="products0" value="{$invoiceItems[0].product_id|htmlSafe}"/>
        {/if}
        <input type="hidden" name="type" value="{if isset($invoice.type_id)}{$invoice.type_id|htmlSafe}{/if}"/>
        <input type="hidden" id="max_items" name="max_items" value="{if isset($lines)}{$lines|htmlSafe}{/if}"/>
    </div>
</form>
